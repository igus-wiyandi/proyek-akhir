<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerhitunganGaji extends Model
{
    use HasFactory;
    protected $table = 'gaji';
    protected $guarded = ['id'];


    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }
}
