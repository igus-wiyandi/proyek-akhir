<?php
namespace App\DTO;

class AbsensiImportDTO
{
    public string $nama;
    public string $departemen;
    public string $tanggal;
    public int $total;

    public function __construct(string $nama, string $departemen, string $tanggal, int $total)
    {
        $this->nama = $nama;
        $this->departemen = $departemen;
        $this->tanggal = $tanggal;
        $this->total = $total;
    }
}
