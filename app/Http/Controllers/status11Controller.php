<?php

namespace App\Http\Controllers;

use App\Models\MapelKelas11;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Status11Controller extends Controller
{
    public function index(Request $request)
    {
        $guru = Guru::where('user_id', Auth::id())->firstOrFail();
        $guruId = $guru->id;
        $mingguOffset = (int) $request->query('minggu', 0);

        $mapel11 = MapelKelas11::with(['status11' => function ($q) use ($guruId) {
            $q->where('guru_id', $guruId);
        }])
        ->where('guru_id', $guruId)
        ->orderBy('tanggal')
        ->orderBy('jam_mulai')
        ->get();

        $mapel11 = $mapel11->map(function ($m) use ($mingguOffset) {
            $hariKe = \Carbon\Carbon::parse($m->tanggal)->dayOfWeekIso;
            $startOfWeek = \Carbon\Carbon::now()->startOfWeek()->addWeeks($mingguOffset);
            $m->tanggal_dihitung = $startOfWeek->copy()->addDays($hariKe - 1)->toDateString();
            return $m;
        });

        $mapel11 = $mapel11->sortBy([
            ['tanggal_dihitung', 'asc'],
            ['jam_mulai', 'asc'],
        ])->values();

        return view('status11.index', compact('mapel11', 'mingguOffset'));
    }

    public function create() {}
    public function store(Request $request) {}
    public function show(string $id) {}
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}
}
