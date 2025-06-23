<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class perhitungan_gaji extends Model
{
    use HasFactory;
    protected $table = 'perhitungan_gaji';
    protected $guarded = ['id'];

  public function guru()
    {
        return $this->belongsTo(guru::class, 'id_guru');
    }

    public function absensi()
    {
        return $this->belongsTo(absensi::class, 'id_absensi');
    }

    public function mapel()
    {
        return $this->belongsTo(mapel::class, 'id_mapel');
    }

    public function jabatan()
    {
        return $this->belongsTo(jabatan::class, 'id_jabatan');
    }
}
