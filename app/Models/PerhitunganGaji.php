<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerhitunganGaji extends Model
{
    use HasFactory;
    protected $table = 'gaji';
    protected $guarded = ['id'];

    // WAJIB: Casting JSON ke Array agar mudah di-looping di Blade
    protected $casts = [
        'rincian_mingguan' => 'array',
        'periode_mulai' => 'date',
        'periode_akhir' => 'date',
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }
}
