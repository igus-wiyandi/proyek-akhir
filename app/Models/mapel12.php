<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class mapel12 extends Model
{
    use HasFactory;
    protected $table = 'mapel12';

    protected $fillable = [
        'nama',
        'jam_mulai',
        'jam_selesai',
        'tanggal',
        'guru_id',
    ];
    public function guru()
    {
        return $this->belongsTo(guru::class, 'guru_id');
    }

    public function status12()
    {
        return $this->hasMany(Status12::class, 'mapel12_id');
    }
}
