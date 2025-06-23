<?php

namespace App\Http\Controllers;
use App\Models\mapel12;
use App\Models\guru;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class mapel12Controller extends Controller
{
    public function index(Request $request)
    {
        $guru12 = guru::all();

        $mingguOffset = (int) $request->query('minggu', 0);
        $startOfWeek = Carbon::now()->startOfWeek()->addWeeks($mingguOffset);

        $mapel12 = mapel12::with('guru')->orderBy('tanggal')->orderBy('jam_mulai')->get();


        $mapel12->transform(function ($item) use ($startOfWeek) {
            $tanggal = Carbon::parse($item->tanggal);
            $hariKe = $tanggal->dayOfWeekIso - 1;
            $item->tanggal_dihitung = $startOfWeek->copy()->addDays($hariKe)->toDateString();
            return $item;
        })->sortBy(function ($item) {
            return $item->tanggal_dihitung . ' ' . $item->jam_mulai;
        })->values();


  $perPage = 7;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $mapel12->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginatedMapel = new LengthAwarePaginator(
            $currentItems,
            $mapel12->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('mapel12.index', [
            'mapel12' => $paginatedMapel,
            'guru12' => $guru12,
            'mingguOffset' => $mingguOffset
        ]);
    }

    public function create(){
        $mapel12 = mapel12::all();
        return view('mapel12.create', compact('mapel12'));
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

        mapel12:: create([
            "nama" => $request->nama,
            "jam_mulai" => $jam_mulai,
            "jam_selesai" => $jam_selesai,
            "tanggal" => $request->tanggal,
            "guru_id" => $request->guru_id,
        ]);


        return redirect()->route('mapel12.index')->with('success', 'Pelajaran Berhasil Ditambah');
    }

    public function edit($id)
    {
        $mapel12 = mapel12::find($id);
        $mapel12->tanggal = Carbon::parse($mapel12->tanggal)->format('Y-m-d');
        return view('mapel12.edit', compact('mapel12'));
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

    $mapel12 = mapel12::findOrFail($id);
    $mapel12->update([
        'nama' => $request->nama,
        'tanggal' => $request->tanggal,
        'jam_mulai' => $jam_mulai,
        'jam_selesai' => $jam_selesai,
    ]);

    return redirect()->route('mapel12.index')->with('success', 'Pelajaran Berhasil Diubah');
}

    public function destroy(mapel12 $mapel12)
    {
        $mapel12->delete();
        return redirect()->route('mapel12.index')->with('success', 'Pelajaran Berhasil Dihapus');
    }

    public function showPilihGuru12(Request $request, $id)
    {
        $mapel12 = mapel12::findOrFail($id);
        $guru12 = guru::all();
        $mapel12->guru_id = $request->input('guru_id') ?: null;
        $mapel12->save();
        return view('mapel12.index', compact('mapel12', 'guru12'));
    }

    public function simpanGuru12(Request $request, $id)
    {
        $mapel12 = mapel12::findOrFail($id);
        $mapel12->guru_id = $request->guru_id;
        $mapel12->save();

        return redirect()->route('mapel12.index')->with('success', 'Guru berhasil dipilih');
    }
}
