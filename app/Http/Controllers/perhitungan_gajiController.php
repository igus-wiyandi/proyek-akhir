<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\perhitungan_gaji;
use App\Models\absensi;
use App\Models\guru;
use App\Models\jabatan;
use Carbon\Carbon;

class perhitungan_gajiController extends Controller
{
    public function index(Request $request)
    {
        $isAdmin = Session::get('isAdmin');
        $perhitungan_gaji = perhitungan_gaji::with('jabatan')
            ->orderBy('created_at', 'desc');


        return view('gaji.index', [
            'gaji' => $perhitungan_gaji,
            'isAdmin' => $isAdmin,
            'dataGaji' => [],
        ]);
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

        $totalHariKerja = $startDate->diffInDaysFiltered(function($date) {
            return !$date->isWeekend();
        }, $endDate);

        $guru = guru::with(['jabatan.kategori', 'latestAbsensi'])->get();

        if (!$isAdmin) {
            $guru = guru::with(['jabatan.kategori', 'latestAbsensi'])
                ->where('id', Session::get('ambilUser')->id)
                ->get();
        }


        $absensi = absensi::whereBetween('tanggal', [$startDate, $endDate])->get();

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

            $jabatan = $item->jabatan()->latest()->first();
            $kategori = $jabatan->kategori ?? null;

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
                'periode' => $startDate->format('d/m/Y').' - '.$endDate->format('d/m/Y')
            ];
        }

        return view('gaji.index', [
            'dataGaji' => $dataGaji,
            'periode' => $startDate->format('d/m/Y').' - '.$endDate->format('d/m/Y'),
            'isAdmin' => $isAdmin,
        ]);
    }
}
