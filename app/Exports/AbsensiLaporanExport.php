<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AbsensiLaporanExport implements FromView, ShouldAutoSize
{
    protected $reports;
    protected $startDate;
    protected $endDate;

    public function __construct($reports, $startDate, $endDate)
    {
        $this->reports = $reports;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function view(): View
    {
        return view('absensi.export_excel', [
            'reports' => $this->reports,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ]);
    }
}
