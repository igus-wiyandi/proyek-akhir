<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class absensi extends Model
{
    use HasFactory;
    protected $table = 'absensi';
    protected $guarded = ['id'];

    public function guru()
    {
        return $this->belongsTo(guru::class, 'id_guru');
    }

    public function jabatan()
    {
        return $this->belongsTo(jabatan::class, 'id_jabatan');
    }

}
