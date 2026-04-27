<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;
    protected $table = 'laporan';
    protected $guarded = ['id'];

    public function perhitungan_gaji()
    {
        return $this->belongsTo(PerhitunganGaji::class, 'id_perhitungan_gaji');
    }

    public function absensi()
    {
        return $this->belongsTo(Absensi::class, 'id_absensi');
    }
}
