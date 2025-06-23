<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class mapel11 extends Model
{
    use HasFactory;
    protected $table = 'mapel11';

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
    public function status11()
    {
        return $this->hasMany(Status11::class, 'mapel11_id');
    }
}
