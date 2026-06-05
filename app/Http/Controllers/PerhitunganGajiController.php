<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\PerhitunganGaji;
use App\Models\Absensi;
use App\Models\Guru;
use App\Models\Jabatan;
use Carbon\Carbon;


class PerhitunganGajiController extends Controller
{
    public function index(Request $request)
    {
        $isAdmin = Session::get('isAdmin');
        return view('gaji.index', compact('isAdmin'));
    }

    public function laporanGaji(Request $request)
    {
        $isAdmin = Session::get('isAdmin');
        $dataGaji = collect(); // Default tabel kosong saat pertama dibuka

        // Jika tombol "Cari Riwayat" ditekan
        if ($request->has('start') && $request->has('end')) {
            $dataGaji = PerhitunganGaji::with('guru')
                ->whereBetween('periode_mulai', [$request->start, $request->end])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('gaji.laporanGaji', compact('dataGaji', 'isAdmin'));
    }
    public function filterByDateRange(Request $request)
    {
        $request->validate([
            'start' => 'required|date_format:m/d/Y',
            'end' => 'required|date_format:m/d/Y|after_or_equal:start'
        ]);
        $isAdmin = Session::get('isAdmin');

        $startDate = Carbon::createFromFormat('m/d/Y', $request->start)->startOfDay();
        $endDate = Carbon::createFromFormat('m/d/Y', $request->end)->endOfDay();

        $totalHariKerja = $startDate->diffInDaysFiltered(function ($date) {
            return !$date->isWeekend();
        }, $endDate);

        $guru = Guru::with(['jabatan.kategori', 'latestAbsensi'])->get();

        if (!$isAdmin) {
            $guru = Guru::with(['jabatan.kategori', 'latestAbsensi'])
                ->where('id', Session::get('ambilUser')->id)
                ->get();
        }


        $absensi = Absensi::whereBetween('tanggal', [$startDate, $endDate])->get();

        $dataGaji = [];
        $tarifPerJam = 45000;
        $menitPerHari = 480;

        foreach ($guru as $item) {
            $absensiGuru = $absensi->where('guru_id', $item->id);

            $totalMenitHadir = $absensiGuru->sum('menit');
            $totalMenitNormal = $totalHariKerja * $menitPerHari;
            $menitTidakHadir = $totalMenitNormal - $totalMenitHadir;

            $jamHadir = $totalMenitHadir / 60;
            $jamTidakHadir = $menitTidakHadir / 60;

            $gajiMengajar = $jamHadir * $tarifPerJam;

            $jabatan = $item->Jabatan()->latest()->first();
            $kategori = $jabatan->Kategori ?? null;

            $honorTambahan = $jabatan->honor ?? 0;
            $gajiTotal = $gajiMengajar + $honorTambahan;

            $dataGaji[] = [
                'guru' => $item->nama,
                'guru_id' => $item->id,
                'jabatan' => $jabatan->nama_jabatan ?? 'Tidak Ada Jabatan',
                'kategori' => $kategori->nama ?? 'Tidak Ada Kategori',
                'total_hari' => $totalHariKerja,
                'menit_hadir' => $totalMenitHadir,
                'menit_tidak_hadir' => $menitTidakHadir,
                'jam_hadir' => round($jamHadir, 2),
                'jam_tidak_hadir' => round($jamTidakHadir, 2),
                'gaji_mengajar' => $gajiMengajar,
                'honor_tambahan' => $honorTambahan,
                'gaji_total' => $gajiTotal,
                'tarif_per_jam' => $tarifPerJam,
                'periode' => $startDate->format('d/m/Y') . ' - ' . $endDate->format('d/m/Y')
            ];
        }

        return view('gaji.index', [
            'dataGaji' => $dataGaji,
            'periode' => $startDate->format('d/m/Y') . ' - ' . $endDate->format('d/m/Y'),
            'isAdmin' => $isAdmin,
        ]);
    }

    public function generate(Request $request)
    {
        $request->validate([
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
        ]);

        $isAdmin = Session::get('isAdmin');
        $start = Carbon::parse($request->start);
        $end = Carbon::parse($request->end);

        // Aturan Penggajian Sesuai Kesepakatan
        $jamStandarSebulan = 53.2;
        $tarifPerJam = 45000;

        $semuaGuru = Guru::all();

        DB::beginTransaction();
        try {
            foreach ($semuaGuru as $guru) {
                // 1. Tarik Absensi Guru di rentang tanggal tersebut
                $absensi = Absensi::where('guru_id', $guru->id)
                    ->whereBetween('tanggal', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                    ->orderBy('tanggal', 'asc')
                    ->get();

                // Hitung Total Potongan Jam
                $totalPotongan = $absensi->sum('potongan_jam');

                // Hitung Jam Bersih (Pastikan tidak minus)
                $jamBersih = max(0, $jamStandarSebulan - $totalPotongan);

                $gajiMengajar = $jamBersih * $tarifPerJam;

                $tunjanganTambahan = 0;
                // if ($guru->kategori_id == 2) { 
                //     $tunjanganTambahan = 350000;
                // }

                $totalGajiBersih = $gajiMengajar + $tunjanganTambahan;

                // 2. Buat Rincian Mingguan (Grouping)
                $rincianMingguan = [];
                $groupedByWeek = $absensi->groupBy(function ($date) {
                    return Carbon::parse($date->tanggal)->weekOfMonth;
                });

                foreach ($groupedByWeek as $mingguKe => $dataMingguIni) {
                    $potonganMinggu = $dataMingguIni->sum('potongan_jam');
                    $rincianMingguan["Minggu ke-{$mingguKe}"] = [
                        'potongan_jam' => $potonganMinggu,
                        'detail_absen' => $dataMingguIni->where('potongan_jam', '>', 0)->map(function ($item) {
                            return $item->tanggal . ' (' . $item->status_kehadiran . ') - Potong: ' . $item->potongan_jam . ' Jam';
                        })->toArray()
                    ];
                }

                // 3. Simpan ke Tabel Gaji (updateOrCreate agar tidak dobel jika di-generate ulang)
                PerhitunganGaji::updateOrCreate(
                    [
                        'guru_id' => $guru->id,
                        'periode_mulai' => $start->format('Y-m-d'),
                        'periode_akhir' => $end->format('Y-m-d'),
                    ],
                    [
                        'total_jam_standar' => $jamStandarSebulan,
                        'total_potongan_jam' => $totalPotongan,
                        'total_jam_bersih' => $jamBersih,
                        'tarif_per_jam' => $tarifPerJam,
                        'tunjangan_tambahan' => $tunjanganTambahan,
                        'total_gaji_bersih' => $totalGajiBersih,
                        'rincian_mingguan' => $rincianMingguan,
                        'status_pembayaran' => 'Belum Dibayar',
                    ]
                );
            }

            DB::commit();
            return redirect()->route('perhitungan_gaji.index')->with('success', 'Gaji periode ini berhasil di-generate dan disimpan ke database!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal generate gaji: ' . $e->getMessage());
        }
    }

    public function preview(Request $request)
    {
        $request->validate([
            'nama_periode' => 'required|string',
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
        ]);

        $isAdmin = Session::get('isAdmin');
        $start = Carbon::parse($request->start);
        $end = Carbon::parse($request->end);

        // KONSTANTA BARU YANG LEBIH AKURAT
        $tarifPerJam = 45000;
        $standarJamPerHari = 13.3 / 5; // Hasilnya 2.66 jam per hari

        $semuaGuru = Guru::all();
        $previewGaji = [];

        foreach ($semuaGuru as $guru) {
            $absensi = Absensi::where('guru_id', $guru->id)
                ->whereBetween('tanggal', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                ->orderBy('tanggal', 'asc')
                ->get();

            if ($absensi->isEmpty()) continue;

            // Kelompokkan berdasarkan Tahun & Minggu ke-berapa di kalender
            $groupedByWeek = $absensi->groupBy(function ($date) {
                return Carbon::parse($date->tanggal)->format('Y-W');
            });
            $groupedByWeek = $groupedByWeek->sortKeys()->values();

            $rincianMingguan = [];
            $totalJamStandarBulanIni = 0;
            $totalJamBersihBulanIni = 0;
            $totalPotonganBulanIni = 0;

            foreach ($groupedByWeek as $index => $dataMingguIni) {
                $mingguKe = $index + 1;

                // (Contoh: Minggu 1 ada 5 hari, Minggu 5 cuma 2 hari)
                $jumlahHariKerja = $dataMingguIni->count();

                // Standar mingguan = Hari Kerja x 2.66
                $jamStandarMingguan = round($jumlahHariKerja * $standarJamPerHari, 2);

                $potonganMinggu = $dataMingguIni->sum('potongan_jam');
                $jamBersihMingguan = max(0, $jamStandarMingguan - $potonganMinggu);
                $gajiMingguan = $jamBersihMingguan * $tarifPerJam;

                // Akumulasikan ke total bulanan
                $totalJamStandarBulanIni += $jamStandarMingguan;
                $totalPotonganBulanIni += $potonganMinggu;
                $totalJamBersihBulanIni += $jamBersihMingguan;

                $rincianMingguan["Minggu ke-{$mingguKe}"] = [
                    'jam_standar' => $jamStandarMingguan,
                    'potongan_jam' => $potonganMinggu,
                    'jam_bersih' => $jamBersihMingguan,
                    'gaji_mingguan' => $gajiMingguan,
                    'detail_absen' => $dataMingguIni->where('potongan_jam', '>', 0)->map(function ($item) {
                        return Carbon::parse($item->tanggal)->format('d M') . ' (' . $item->status_kehadiran . ') - Potong: ' . $item->potongan_jam . ' Jam';
                    })->toArray()
                ];
            }

            // Hitung Total Gaji Akhir
            $gajiMengajar = $totalJamBersihBulanIni * $tarifPerJam;
            $tunjanganTambahan = \App\Models\Jabatan::where('guru_id', $guru->id)->sum('honor');
            $totalGajiBersih = $gajiMengajar + $tunjanganTambahan;

            $previewGaji[] = [
                'guru_id' => $guru->id,
                'nama_guru' => $guru->nama,
                'nama_periode' => $request->nama_periode,
                'periode_mulai' => $start->format('Y-m-d'),
                'periode_akhir' => $end->format('Y-m-d'),
                'jam_bersih' => $totalJamBersihBulanIni,
                'total_potongan_jam' => $totalPotonganBulanIni,
                'tunjangan_tambahan' => $tunjanganTambahan,
                'total_gaji_bersih' => $totalGajiBersih,
                'rincian_mingguan' => json_encode($rincianMingguan)
            ];
        }

        if (empty($previewGaji)) {
            return back()->with('error', 'Tidak ada data absensi di rentang tanggal tersebut.');
        }

        return view('gaji.index', [
            'isAdmin' => $isAdmin,
            'previewGaji' => $previewGaji,
            'requestData' => $request->all()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate(['data' => 'required|array']);

        DB::beginTransaction();
        try {
            foreach ($request->data as $item) {
                PerhitunganGaji::updateOrCreate(
                    [
                        'guru_id' => $item['guru_id'],
                        'periode_mulai' => $item['periode_mulai'],
                        'periode_akhir' => $item['periode_akhir'],
                    ],
                    [
                        'nama_periode' => $item['nama_periode'],
                        'total_jam_standar' => 53.2,
                        'total_potongan_jam' => $item['total_potongan_jam'],
                        'total_jam_bersih' => $item['jam_bersih'],
                        'tarif_per_jam' => 45000,
                        'tunjangan_tambahan' => $item['tunjangan_tambahan'],
                        'total_gaji_bersih' => $item['total_gaji_bersih'],
                        'rincian_mingguan' => json_decode($item['rincian_mingguan'], true), // Kembalikan ke JSON array
                        'status_pembayaran' => 'Belum Dibayar',
                    ]
                );
            }
            DB::commit();
            return redirect()->route('perhitungan_gaji.index')->with('success', 'Gaji berhasil disimpan ke database!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }
}
