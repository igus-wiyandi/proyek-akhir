<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class GajiRiwayatExport implements FromView, ShouldAutoSize
{
    protected $dataGaji;
    protected $start;
    protected $end;

    public function __construct($dataGaji, $start, $end)
    {
        $this->dataGaji = $dataGaji;
        $this->start = $start;
        $this->end = $end;
    }

    public function view(): View
    {
        return view('gaji.export_excel', [
            'dataGaji' => $this->dataGaji,
            'start' => $this->start,
            'end' => $this->end,
        ]);
    }
}
