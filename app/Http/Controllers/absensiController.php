<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\absensi;
use App\Imports\AbsensiImport;
use App\Models\guru;
use App\Models\jabatan;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
class absensiController extends Controller
{
    public function index()
    {
        $isAdmin = Session::get('isAdmin');
        $absensi = absensi::with('guru')->paginate(5);
        if(!$isAdmin){
            $absensi = absensi::with('guru')
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
            'file' => 'required|mimes:xlsx,xls|max:2048',
        ]);
        $isAdmin = Session::get('isAdmin');

        try {
            $import = new AbsensiImport();
            $data = Excel::toArray($import, $request->file('file'));

            // nama sheet yang digunakan
            $processedData = $data['Exception Stat.'] ?? [];
            $cleanData = array_slice($processedData, 5);
            foreach ($cleanData as $key => $row) {
                $cleanData[$key] = [
                    'nama' => $row[1] ?? '',
                    'departemen' => $row[2] ?? '',
                    'tanggal' => $row[3] ?? '',
                    'total' => $row[11] ?? '',
                ];
            }

            return view('absensi.create', ['dataAbsensi' => $cleanData, 'isAdmin' => $isAdmin]);
        } catch (\Exception $e) {
            return back()->withError('Gagal memproses file. Pastikan format sesuai.');
        }
    }

    public function store(Request $request)
    {
        $isAdmin = Session::get('isAdmin');
        $success = 0;
        $failed = 0;
        $namaGuruDB = guru::pluck('nama', 'id')->map(function ($nama) {
            return explode(',', $nama)[0];
        })->toArray();
        foreach ($request->data as $data) {
            $namaGuru = explode(',', $data['nama'])[0];

            $guruId = array_search($namaGuru, $namaGuruDB);
            if (!$guruId) {
                continue;
            }
            $jabatanId = jabatan::where('guru_id', $guruId)->pluck('id')->first();

            if (!$guruId || !$jabatanId) {
                continue;
            }
            $condition = absensi::where('guru_id', $guruId)
                ->where('tanggal', $data['tanggal'])
                ->exists();
            $data['menit'] = $data['menit'] - 60;
            if ($data['menit'] < 0) {
                $data['menit'] = 0;
            }

            if (!$condition) {
                Absensi::create([
                    'guru_id' => $guruId,
                    'jabatan_id' => $jabatanId,
                    'tanggal' => $data['tanggal'],
                    'menit' => $data['menit'],
                    'deskripsi' => $data['menit'] == '480' ? 'alpa' : ($data['menit'] > 0 ? 'izin' : 'hadir'),
                ]);
                $success++;
                continue;
            }
            $failed++;
        }

        return redirect()->route('absensi.index')->with('success', $success . ' Data Absensi berhasil disimpan!')->with('isAdmin', $isAdmin);
    }
}
