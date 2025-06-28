<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class AbsensiImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Exception Stat.' => new ExceptionStatSheetImporter(),
        ];
    }
}

class ExceptionStatSheetImporter implements ToCollection
{
    public function collection(Collection $rows)
    {
        $data = [];
        $startProcessing = false;

        foreach ($rows as $row) {
            if (!$startProcessing && $row->filter()->count() > 0) {
                $headerRow = $row->take(4)->toArray();
                if (in_array('ID', $headerRow) && in_array('Nama', $headerRow) && in_array('Departemen', $headerRow)) {
                    $startProcessing = true;
                    continue;
                }
            }

            if ($startProcessing && is_numeric($row[0])) {
                $data[] = [
                    'nama'       => $row[1] ?? '',
                    'departemen' => $row[2] ?? '',
                    'tanggal'    => $row[3] ?? '',
                    'total'      => (int)($row[11] ?? 0)
                ];
            }
        }

        return $data;
    }
}
