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
use Carbon\CarbonPeriod;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class AbsensiController extends Controller
{
    public function index()
    {
        $isAdmin = Session::get('isAdmin');
        $absensi = Absensi::with('guru')->paginate(5);
        if (!$isAdmin) {
            $absensi = Absensi::with('guru')
                ->whereHas('guru', function ($query) {
                    $query->where('id', Session::get('ambilUser')->id);
                })->paginate(5);
        }

        return view('absensi.index', [
            'absensi' => $absensi,
            'isAdmin' => $isAdmin
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
            'tanggal_libur'   => 'nullable|array',         // Beri tahu Laravel ini adalah Array
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

                // --- Lanjutkan proses datanya di bawah ini ---

                // Ambil sheet pertama (index 0) apa pun namanya
                $processedData = $data[0] ?? [];

                $rawExcelData = array_slice($processedData, 1); // Skip baris header

                // dd($rawExcelData);
                // ---------------------------------------------------------
                // FASE 1: MAPPING DATA EXCEL (Berdasarkan NIK)
                // ---------------------------------------------------------
                // ---------------------------------------------------------
                // FASE 1: MAPPING DATA EXCEL (Berdasarkan NIK)
                // ---------------------------------------------------------
                $excelMapped = [];
                foreach ($rawExcelData as $row) {
                    // Sesuaikan angka index 2 dengan kolom NIK di Excel (Di gambarmu index 2 bernilai null)
                    $nikExcel = trim($row[2] ?? '');

                    // Ambil data mentah dari Excel
                    $tanggalRaw = $row[5] ?? '';
                    $jamMasukRaw = $row[9] ?? '';
                    $jamPulangRaw = $row[10] ?? '';
                    $jmlJamKerjaRaw = $row[17] ?? '';

                    // TRANSLATE ANGKA EXCEL KE FORMAT YANG BISA DIBACA
                    // Jika isinya angka (desimal excel), kita konversi. Jika teks biasa, biarkan.
                    $tanggalExcel = is_numeric($tanggalRaw)
                        ? Date::excelToDateTimeObject($tanggalRaw)->format('Y-m-d')
                        : trim($tanggalRaw);

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
                            'jml_jam_kerja' => $jmlhJamKerjaExcel, // Dibiarkan saja karena kita pakai logika batas 8 jam
                        ];
                    }
                }

                // dd($excelMapped);
                // ---------------------------------------------------------
                // FASE 2: CROSS-CHECKING DENGAN MASTER GURU (Menggunakan NIK)
                // ---------------------------------------------------------
                $cleanData = [];
                $semuaGuru = Guru::all();


                $period = CarbonPeriod::create($request->tanggal_mulai, $request->tanggal_akhir);
                // TAMBAHAN: Sabuk Pengaman Validasi Tanggal
                $adaDataYangCocok = false;

                // 2. Tangkap array tanggal libur (berikan array kosong [] jika null)
                $tanggalLibur = $request->tanggal_libur ?? [];

                foreach ($semuaGuru as $guru) {
                    foreach ($period as $date) {
                        if ($date->isWeekend()) {
                            continue;
                        }

                        // dd($guru->nama, $date);
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

                // TAMBAHAN: Pengecekan Akhir Sabuk Pengaman
                // Jika setelah muter-muter seluruh guru ternyata tidak ada satupun data yang cocok
                if ($adaDataYangCocok === false) {
                    // dd('error', 'Gagal! Tanggal pada file Excel yang diunggah tidak sesuai dengan rentang tanggal yang Anda pilih di form.');
                    return back()->with('error', 'Gagal! Tanggal pada file Excel yang diunggah tidak sesuai dengan rentang tanggal yang Anda pilih di form.');
                }

                // dd($cleanData);
                return view('absensi.create', ['dataAbsensi' => $cleanData, 'isAdmin' => $isAdmin]);
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
            // Sesuaikan 'absensi.index' dengan nama route tabel utamamu
            return redirect()->route('absensi.index')
                ->with('success', 'Data absensi berhasil disimpan ke database!');
        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan semua simpanan jika terjadi error di tengah jalan

            return back()->with('error', 'Gagal menyimpan data ke database: ' . $e->getMessage());
        }
    }
}
