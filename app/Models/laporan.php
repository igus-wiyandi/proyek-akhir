<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class laporan extends Model
{
    use HasFactory;
    protected $table = 'laporan';
    protected $guarded = ['id'];

    public function perhitungan_gaji()
    {
        return $this->belongsTo(perhitungan_gaji::class, 'id_perhitungan_gaji');
    }

    public function absensi()
    {
        return $this->belongsTo(absensi::class, 'id_absensi');
    }
}
