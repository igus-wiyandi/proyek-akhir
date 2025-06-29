<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class perhitungan_gaji extends Model
{
    use HasFactory;
    protected $table = 'gaji';
    protected $guarded = ['id'];


    public function jabatan()
    {
        return $this->belongsTo(jabatan::class, 'jabatan_id');
    }
}
