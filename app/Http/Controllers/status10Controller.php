<?php

namespace App\Http\Controllers;
use App\Models\mapel;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class status10Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function index(Request $request)
    {
        $guruId = Session::get('guru_id');
        $mingguOffset = (int) $request->query('minggu', 0);

        $mapel = mapel::with(['status' => function ($q) use ($guruId) {
            $q->where('guru_id', $guruId);
        }])
        ->where('guru_id', $guruId)
        ->orderBy('tanggal')
        ->orderBy('jam_mulai')
        ->get();

        $mapel = $mapel->map(function ($m, $index) use ($mingguOffset) {
            $hariKe = \Carbon\Carbon::parse($m->tanggal)->dayOfWeekIso;
            $startOfWeek = \Carbon\Carbon::now()->startOfWeek()->addWeeks($mingguOffset);
            $m->tanggal_dihitung = $startOfWeek->copy()->addDays($hariKe - 1)->toDateString();
            return $m;
        });

        $mapel = $mapel->sortBy([
            ['tanggal_dihitung', 'asc'],
            ['jam_mulai', 'asc'],
        ])->values();

        return view('status10.index', compact('mapel', 'mingguOffset'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
