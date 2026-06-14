<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Imports\AbsensiImport;
use App\Models\Guru;
use App\Models\Jabatan;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use PhpOffice\PhpSpreadsheet\Shared\Date;

use App\Exports\AbsensiLaporanExport;
use Barryvdh\DomPDF\Facade\Pdf;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $isAdmin = Session::get('isAdmin');
        $userLokal = Session::get('ambilUser');

        // 1. Tangkap Filter dari Request (Set default ke bulan ini jika kosong)
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $status = $request->input('status');
        $guruId = $request->input('guru_id');

        // 2. Buat Base Query
        $query = Absensi::with('guru')->whereBetween('tanggal', [$startDate, $endDate]);

        // 3. Terapkan Filter Berdasarkan Hak Akses
        if (!$isAdmin) {
            $query->where('guru_id', $userLokal->id);
        } else {
            if ($guruId) {
                $query->where('guru_id', $guruId);
            }
        }

        // 4. Terapkan Filter Status (Hadir / Alpa / Setengah Hari)
        if ($status) {
            $query->where('status_kehadiran', $status);
        }

        // 5. Hitung Statistik (Clone query agar pagination di bawah tidak rusak)
        $statsQuery = clone $query;
        $totalHadir = (clone $statsQuery)->where('status_kehadiran', 'Hadir')->count();
        $totalAlpa = (clone $statsQuery)->where('status_kehadiran', 'Alpa')->count();
        // Hitung total jam yang dipotong untuk estimasi pemotongan gaji
        $totalPotongan = (clone $statsQuery)->sum('potongan_jam');

        // 6. Ambil Data dengan Pagination 
        $absensi = $query->orderBy('tanggal', 'desc')->paginate(10)->withQueryString();

        // 7. Ambil Daftar Guru untuk Dropdown Filter (Hanya untuk Admin)
        $listGuru = $isAdmin ? Guru::orderBy('nama')->get() : [];

        return view('absensi.index', [
            'absensi' => $absensi,
            'isAdmin' => $isAdmin,
            'listGuru' => $listGuru,
            'totalHadir' => $totalHadir,
            'totalAlpa' => $totalAlpa,
            'totalPotongan' => $totalPotongan,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'selectedStatus' => $status,
            'selectedGuru' => $guruId
        ]);
    }
    public function create()
    {
        $isAdmin = Session::get('isAdmin');

        $absensi = null;
        return view('absensi.create', compact('absensi', 'isAdmin'));
    }
    public function preview(Request $request)
    {
        $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_mulai',
            'file' => 'required|file|max:20000',
            'tanggal_libur'   => 'nullable|array',
            'tanggal_libur.*' => 'nullable|date',
        ]);

        $isAdmin = Session::get('isAdmin');


        try {
            if ($request->hasFile('file') && $request->file('file')->isValid()) {

                $file = $request->file('file');


                // 1. Pindahkan file dari C:\Windows\Temp ke folder storage/app/temp_excel
                $namaFile = time() . '_' . $file->getClientOriginalName();
                // Ini akan memindahkan file langsung ke folder storage/app/temp_excel
                $file->move(storage_path('app/temp_excel'), $namaFile);


                // 2. Dapatkan full path dari file yang sudah dipindah
                $fullPath = storage_path('app/temp_excel/' . $namaFile);

                // 3. Baca file menggunakan library Excel dari storage menggunakan class import basic bawaan
                $import = new class implements \Maatwebsite\Excel\Concerns\ToArray {
                    public function array(array $array) {}
                };

                $data = Excel::toArray($import, $fullPath);

                // Hapus file temporary menggunakan unlink bawaan PHP
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                }

                // Ambil sheet pertama (index 0) apa pun namanya
                $processedData = $data[0] ?? [];

                $rawExcelData = array_slice($processedData, 1); // Skip baris header

                // ---------------------------------------------------------
                // FASE 1: MAPPING DATA EXCEL (Berdasarkan NIK)
                // ---------------------------------------------------------
                $excelMapped = [];
                foreach ($rawExcelData as $row) {
                    // Sesuaikan angka index 2 dengan kolom NIK di Excel 
                    $nikExcel = trim($row[2] ?? '');

                    // Ambil data mentah dari Excel
                    $tanggalRaw = $row[5] ?? '';
                    $jamMasukRaw = $row[9] ?? '';
                    $jamPulangRaw = $row[10] ?? '';
                    $jmlJamKerjaRaw = $row[17] ?? '';

                    // TRANSLATE ANGKA EXCEL KE FORMAT YANG BISA DIBACA
                    // Jika isinya angka (desimal excel), kita konversi. Jika teks biasa, biarkan.
                    // (Support Format d/m/Y atau d-m-Y)
                    if (is_numeric($tanggalRaw)) {
                        $tanggalExcel = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tanggalRaw)->format('Y-m-d');
                    } else {
                        // Standarisasi pemisah: ubah semua garis miring (/) menjadi strip (-)
                        $cleanDate = str_replace('/', '-', trim($tanggalRaw));

                        try {
                            // 1. Coba paksa baca dengan format Indonesia (Hari-Bulan-Tahun)
                            $tanggalExcel = \Carbon\Carbon::createFromFormat('d-m-Y', $cleanDate)->format('Y-m-d');
                        } catch (\Exception $e) {
                            try {
                                // 2. Jika gagal (misal datanya sudah Y-m-d dari sananya), biarkan Carbon menebak
                                $tanggalExcel = \Carbon\Carbon::parse($cleanDate)->format('Y-m-d');
                            } catch (\Exception $e2) {
                                // 3. Fallback mentah jika format benar-benar hancur
                                $tanggalExcel = $tanggalRaw;
                            }
                        }
                    }

                    $jamMasukExcel = is_numeric($jamMasukRaw)
                        ? Date::excelToDateTimeObject($jamMasukRaw)->format('H:i')
                        : trim($jamMasukRaw);

                    $jamPulangExcel = is_numeric($jamPulangRaw)
                        ? Date::excelToDateTimeObject($jamPulangRaw)->format('H:i')
                        : trim($jamPulangRaw);

                    $jmlhJamKerjaExcel = is_numeric($jmlJamKerjaRaw)
                        ? Date::excelToDateTimeObject($jmlJamKerjaRaw)->format('H:i')
                        : trim($jmlJamKerjaRaw);

                    // Masukkan ke array jika NIK dan Tanggal tidak kosong
                    if ($nikExcel != '' && $tanggalExcel != '') {
                        $excelMapped[$nikExcel][$tanggalExcel] = [
                            'nama_di_excel' => trim($row[3] ?? ''),
                            'jam_masuk' => $jamMasukExcel,
                            'jam_pulang' => $jamPulangExcel,
                            'jml_jam_kerja' => $jmlhJamKerjaExcel,
                        ];
                    }
                }

                // ---------------------------------------------------------
                // FASE 2: CROSS-CHECKING DENGAN MASTER GURU (Menggunakan NIK)
                // ---------------------------------------------------------
                $cleanData = [];
                $listGuru = Guru::orderBy('nama', 'asc')->get();
                $semuaGuru = Guru::all();


                $period = CarbonPeriod::create($request->tanggal_mulai, $request->tanggal_akhir);

                $adaDataYangCocok = false;

                //  Tangkap array tanggal libur (berikan array kosong [] jika null)
                $tanggalLibur = $request->tanggal_libur ?? [];

                foreach ($semuaGuru as $guru) {
                    foreach ($period as $date) {
                        if ($date->isWeekend()) {
                            continue;
                        }

                        $tglStr = $date->format('Y-m-d');
                        $nikGuru = $guru->nik;
                        $namaGuru = $guru->nama;
                        $guruId = $guru->id;

                        // Cek apakah $tglStr ada di dalam array $tanggalLibur
                        if (in_array($tglStr, $tanggalLibur)) {
                            continue; // Bebas dari hukuman Alpa!
                        }

                        // Lakukan Pencocokan berdasarkan NIK
                        if (isset($excelMapped[$nikGuru][$tglStr])) {

                            // JIKA MASUK KESINI MINIMAL 1 KALI, BERARTI TANGGALNYA BENAR/COCOK
                            $adaDataYangCocok = true;

                            $dataHadir = $excelMapped[$nikGuru][$tglStr];

                            $jamMasuk = trim($dataHadir['jam_masuk']);
                            $jamPulang = trim($dataHadir['jam_pulang']);

                            if ($jamMasuk == '' || $jamPulang == '') {
                                $status = 'Tidak Valid (Lupa Absen)';
                                $potongan = 8;
                                $jamKerjaDiakui = 0;
                            } else {
                                $pulangSingkat = substr($jamPulang, 0, 5);

                                if ($pulangSingkat < '12:00') {
                                    $status = 'Pulang Awal (Tidak Sah)';
                                    $potongan = 8;
                                    $jamKerjaDiakui = 0;
                                } elseif ($pulangSingkat < '16:00') {
                                    $status = 'Setengah Hari';
                                    $potongan = 4;
                                    $jamKerjaDiakui = 4;
                                } else {
                                    $status = 'Hadir';
                                    $potongan = 0;
                                    $jamKerjaDiakui = 8;
                                }
                            }

                            $cleanData[] = [
                                'guru_id' => $guruId,
                                'nik' => $nikGuru,
                                'nama' => $namaGuru,
                                'tanggal' => $tglStr,
                                'jam_masuk' => $jamMasuk,
                                'jam_pulang' => $jamPulang,
                                'jml_jam_kerja' => $jamKerjaDiakui,
                                'status_kehadiran' => $status,
                                'potongan_jam' => $potongan,
                            ];
                        } else {
                            // KONDISI B: Guru Tidak Ada di Excel
                            $cleanData[] = [
                                'guru_id' => $guruId,
                                'nik' => $nikGuru,
                                'nama' => $namaGuru,
                                'tanggal' => $tglStr,
                                'jam_masuk' => '-',
                                'jam_pulang' => '-',
                                'jml_jam_kerja' => '0',
                                'status_kehadiran' => 'Alpa',
                                'potongan_jam' => 8,
                            ];
                        }
                    }
                }

                // Jika setelah muter-muter seluruh guru ternyata tidak ada satupun data yang cocok
                if ($adaDataYangCocok === false) {
                    // dd('error', 'Gagal! Tanggal pada file Excel yang diunggah tidak sesuai dengan rentang tanggal yang Anda pilih di form.');
                    return back()->with('error', 'Gagal! Tanggal pada file Excel yang diunggah tidak sesuai dengan rentang tanggal yang Anda pilih di form.');
                }

                return view('absensi.create', ['dataAbsensi' => $cleanData, 'isAdmin' => $isAdmin, 'listGuru' => $listGuru]);
            } else {
                return back()->with('error', 'File tidak valid atau gagal diunggah.');
            }
        } catch (\Exception $e) {
            // Untuk memastikan kita tahu jika ada error lain dari library Excel
            // dd('Error dari Excel: ' . $e->getMessage());
            return back()->with('error', 'Gagal memproses file: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        // Validasi bahwa 'data' yang dikirim harus berupa array
        $request->validate([
            'data' => 'required|array',
        ]);

        // Menggunakan DB Transaction agar jika ada 1 gagal, gagal semua (aman dari data setengah masuk)
        DB::beginTransaction();

        try {
            foreach ($request->data as $item) {
                // Kita skip jika tidak ada guru_id
                if (empty($item['guru_id'])) {
                    continue;
                }

                // updateOrCreate( [Kondisi Pencarian], [Data yang diupdate/disimpan] )
                Absensi::updateOrCreate(
                    [
                        // Cari berdasarkan Guru ID dan Tanggal
                        'guru_id' => $item['guru_id'],
                        'tanggal' => $item['tanggal'],
                    ],
                    [
                        // Jika ketemu (update) atau tidak ketemu (buat baru) dengan data ini:
                        'jam_masuk'        => $item['jam_masuk'],
                        'jam_pulang'       => $item['jam_pulang'],
                        'jml_jam_kerja'    => $item['jml_jam_kerja'],
                        'status_kehadiran' => $item['status_kehadiran'],
                        'potongan_jam'     => $item['potongan_jam'],
                    ]
                );
            }

            DB::commit(); // Simpan permanen ke database

            // Arahkan kembali ke halaman index absensi dengan pesan sukses
            return redirect()->route('absensi.index')
                ->with('success', 'Data absensi berhasil disimpan ke database!');
        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan semua simpanan jika terjadi error di tengah jalan

            return back()->with('error', 'Gagal menyimpan data ke database: ' . $e->getMessage());
        }
    }

    public function report(Request $request)
    {
        $isAdmin = Session::get('isAdmin');
        $userLokal = Session::get('ambilUser');

        // 1. Atur default rentang tanggal ke bulan berjalan jika form pertama kali dimuat
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $status = $request->input('status');
        $guruId = $request->input('guru_id');

        // 2. Bangun query dasar
        $query = Absensi::with('guru')->whereBetween('tanggal', [$startDate, $endDate]);

        // 3. Batasi hak akses data
        if (!$isAdmin) {
            // Guru biasa hanya bisa melihat laporan miliknya sendiri
            $query->where('guru_id', $userLokal->id);
        } else {
            // Admin bisa memfilter berdasarkan dropdown guru
            if ($guruId) {
                $query->where('guru_id', $guruId);
            }
        }

        // 4. Filter berdasarkan status kehadiran
        if ($status) {
            $query->where('status_kehadiran', $status);
        }

        // 5. Ambil data secara keseluruhan untuk kebutuhan cetak dokumen lap.
        $reports = $query->orderBy('tanggal', 'asc')->get();

        // 6. Ambil daftar master guru untuk pilihan dropdown filter admin
        $listGuru = $isAdmin ? Guru::orderBy('nama')->get() : [];

        return view('absensi.report', [
            'reports' => $reports,
            'listGuru' => $listGuru,
            'isAdmin' => $isAdmin,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'selectedStatus' => $status,
            'selectedGuru' => $guruId
        ]);
    }

    // Fungsi Helper internal untuk menghindari penulisan query filter berulang-ulang
    private function getFilteredReportData(Request $request)
    {
        $isAdmin = Session::get('isAdmin');
        $userLokal = Session::get('ambilUser');

        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $status = $request->input('status');
        $guruId = $request->input('guru_id');

        $query = Absensi::with('guru')->whereBetween('tanggal', [$startDate, $endDate]);

        if (!$isAdmin) {
            $query->where('guru_id', $userLokal->id);
        } else {
            if ($guruId) {
                $query->where('guru_id', $guruId);
            }
        }

        if ($status) {
            $query->where('status_kehadiran', $status);
        }

        return [
            'reports' => $query->orderBy('tanggal', 'asc')->get(),
            'startDate' => $startDate,
            'endDate' => $endDate
        ];
    }

    public function exportExcel(Request $request)
    {
        $data = $this->getFilteredReportData($request);
        $filename = 'Laporan_Absensi_' . $data['startDate'] . '_to_' . $data['endDate'] . '.xlsx';

        return Excel::download(new AbsensiLaporanExport($data['reports'], $data['startDate'], $data['endDate']), $filename);
    }

    public function exportPdf(Request $request)
    {
        $data = $this->getFilteredReportData($request);

        // Set opsi kertas dan load view khusus pdf (tanpa layout sidebar/navbar)
        $pdf = Pdf::loadView('absensi.export_pdf', [
            'reports' => $data['reports'],
            'startDate' => $data['startDate'],
            'endDate' => $data['endDate']
        ])->setPaper('a4', 'portrait');

        $filename = 'Laporan_Absensi_' . $data['startDate'] . '_to_' . $data['endDate'] . '.pdf';
        return $pdf->download($filename);
    }
}
