<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class guru extends Model
{
    use HasFactory;
    protected $table = 'guru';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'no_hp',
        'alamat',

    ];

    public function mapel()
    {
        return $this->hasMany(mapel::class, 'guru_id');
    }


    public function status()
    {
        return $this->hasMany(Status10::class);
    }

    public function status11()
    {
        return $this->hasMany(Status11::class);
    }

    public function status12()
    {
        return $this->hasMany(Status12::class);
    }

    public function jabatan()
    {
        return $this->hasOneThrough(
            jabatan::class,
            absensi::class,
            'guru_id',
            'id',
            'id',
            'jabatan_id'
        );
    }

    public function jabatanTerakhir()
    {
        return $this->belongsTo(jabatan::class, 'jabatan_id')
            ->through('absensi')
            ->whereLatest('tanggal');
    }
    public function latestAbsensi()
    {
        return $this->hasOne(absensi::class, 'guru_id')->latestOfMany('tanggal');
    }
}
