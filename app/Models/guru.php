<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Models\Mapel;
use App\Models\Status10;
use App\Models\Status11;
use App\Models\Status12;
use App\Models\Jabatan;
use App\Models\Absensi;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'guru';

    protected $fillable = [
    'user_id',
    'nama',
    'nik',
    'jenis_kelamin',
    'no_hp',
    'alamat',
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mapel()
    {
        return $this->hasMany(Mapel::class, 'guru_id');
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
        return $this->hasOne(Jabatan::class)->latestOfMany();
    }

    public function latestAbsensi()
    {
        return $this->hasOne(Absensi::class, 'guru_id')->latestOfMany('tanggal');
    }
}
