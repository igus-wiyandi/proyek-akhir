<?php

namespace App\Http\Controllers;
use App\Models\mapel;
use App\Models\guru;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class mapelController extends Controller
{
    public function index(Request $request)
    {
        $guru = guru::all();

        $mingguOffset = (int) $request->query('minggu', 0);
        $startOfWeek = Carbon::now()->startOfWeek()->addWeeks($mingguOffset);

        $mapel = mapel::with('guru')->orderBy('tanggal')->orderBy('jam_mulai')->get();


        $mapel = $mapel->transform(function ($item) use ($startOfWeek) {
            $tanggal = Carbon::parse($item->tanggal);
            $hariKe = $tanggal->dayOfWeekIso - 1;
            $item->tanggal_dihitung = $startOfWeek->copy()->addDays($hariKe)->toDateString();
            return $item;
        })->sortBy(function ($item) {
            return $item->tanggal_dihitung . ' ' . $item->jam_mulai;
        })->values();

        $perPage = 8;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $mapel->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginatedMapel = new LengthAwarePaginator(
            $currentItems,
            $mapel->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('mapel.index', [
            'mapel' => $paginatedMapel,
            'guru' => $guru,
            'mingguOffset' => $mingguOffset
        ]);
    }



    public function create(){
        $mapel= mapel::all();
        return view('mapel.create', compact('mapel'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'nama' => 'required|min:2|string',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i',
            'tanggal' => 'required|date',
            'guru_id' => 'nullable|exists:guru,id',
        ]);
        $jam_mulai = $request->jam_mulai . ':00';
        $jam_selesai = $request->jam_selesai . ':00';

        mapel:: create([
            "nama" => $request->nama,
            "jam_mulai" => $jam_mulai,
            "jam_selesai" => $jam_selesai,
            "tanggal" => $request->tanggal,
            "guru_id" => $request->guru_id,
        ]);

        return redirect()->route('mapel.index')->with('success', 'Pelajaran Berhasil Ditambah');
    }

    public function edit($id)
    {
        $mapel = mapel::find($id);
        $mapel->tanggal = Carbon::parse($mapel->tanggal)->format('Y-m-d');
        return view('mapel.edit', compact('mapel'));
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'nama' => 'required|string|min:2',
        'tanggal' => 'required|date',
        'jam_mulai' => 'date_format:H:i',
        'jam_selesai' => 'date_format:H:i',
    ]);

    $jam_mulai = $request->jam_mulai . ':00';
    $jam_selesai = $request->jam_selesai . ':00';

    $mapel = Mapel::findOrFail($id);
    $mapel->update([
        'nama' => $request->nama,
        'tanggal' => $request->tanggal,
        'jam_mulai' => $jam_mulai,
        'jam_selesai' => $jam_selesai,
    ]);

    return redirect()->route('mapel.index')->with('success', 'Pelajaran Berhasil Diubah');
}


    public function destroy(mapel $mapel)
    {
        $mapel->delete();
        return redirect()->route('mapel.index')->with('success', 'Pelajaran Berhasil Dihapus');
    }

    public function showPilihGuru(Request $request, $id)
    {
        $mapel = mapel::findOrFail($id);
        $guru = guru::all();
        $mapel->guru_id = $request->input('guru_id') ?: null;
        $mapel->save();
        return view('mapel.index', compact('mapel', 'guru'));
    }

    public function simpanGuru(Request $request, $id)
    {
        $mapel = mapel::findOrFail($id);
        $mapel->guru_id = $request->guru_id;
        $mapel->save();

        return redirect('/mapel')->with('success', 'Guru Berhasil Dipilih.');
    }
}
