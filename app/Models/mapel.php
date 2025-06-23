<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class mapel extends Model
{
    use HasFactory;
    protected $table = 'mapel';

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
    public function status()
{
    return $this->hasMany(Status10::class, 'mapel_id');
}


}
