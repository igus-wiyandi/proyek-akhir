<?php

namespace App\Http\Controllers;
use App\Models\mapel11;
use App\Models\guru;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class mapel11Controller extends Controller
{
    public function index(Request $request)
    {
        $guru11 = guru::all();

        $mingguOffset = (int) $request->query('minggu', 0);
        $startOfWeek = Carbon::now()->startOfWeek()->addWeeks($mingguOffset);

        $mapel11 = mapel11::with('guru')->orderBy('tanggal')->orderBy('jam_mulai')->get();


        $mapel11->transform(function ($item) use ($startOfWeek) {
            $tanggal = Carbon::parse($item->tanggal);
            $hariKe = $tanggal->dayOfWeekIso - 1;
            $item->tanggal_dihitung = $startOfWeek->copy()->addDays($hariKe)->toDateString();
            return $item;
        })->sortBy(function ($item) {
            return $item->tanggal_dihitung . ' ' . $item->jam_mulai;
        })->values();

        $perPage = 7;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $mapel11->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginatedMapel = new LengthAwarePaginator(
            $currentItems,
            $mapel11->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('mapel11.index', [
            'mapel11' => $paginatedMapel,
            'guru11' => $guru11,
            'mingguOffset' => $mingguOffset
        ]);
    }

    public function create(){
        $mapel11= mapel11::all();
        return view('mapel11.create', compact('mapel11'));
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

        mapel11:: create([
            "nama" => $request->nama,
            "jam_mulai" => $jam_mulai,
            "jam_selesai" => $jam_selesai,
            "tanggal" => $request->tanggal,
            "guru_id" => $request->guru_id,
        ]);


        return redirect()->route('mapel11.index')->with('success', 'Pelajaran Berhasil Ditambah');
    }

    public function edit($id)
    {
        $mapel11 = mapel11::find($id);
        $mapel11->tanggal = Carbon::parse($mapel11->tanggal)->format('Y-m-d');
        return view('mapel11.edit', compact('mapel11'));
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

    $mapel11 = Mapel11::findOrFail($id);
    $mapel11->update([
        'nama' => $request->nama,
        'tanggal' => $request->tanggal,
        'jam_mulai' => $jam_mulai,
        'jam_selesai' => $jam_selesai,
    ]);

    return redirect()->route('mapel11.index')->with('success', 'Pelajaran Berhasil Diubah');
}

    public function destroy(mapel11 $mapel11)
    {
        $mapel11->delete();
        return redirect()->route('mapel11.index')->with('success', 'Pelajaran Berhasil Dihapus');
    }

    public function showPilihGuru11(Request $request, $id)
    {
        $mapel11 = mapel11::findOrFail($id);
        $guru11 = guru::all();
        $mapel11->guru_id = $request->input('guru_id') ?: null;
        $mapel11->save();
        return view('mapel11.index', compact('mapel11', 'guru11'));
    }

    public function simpanGuru11(Request $request, $id)
    {
        $mapel11 = mapel11::findOrFail($id);
        $mapel11->guru_id = $request->guru_id;
        $mapel11->save();

        return redirect()->route('mapel11.index')->with('success', 'Guru Berhasil Dipilih');
    }
}
