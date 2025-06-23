<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\guru;
use App\Models\mapel12;
class Status12 extends Model
{
    use HasFactory;
    protected $table = 'status12';

    protected $fillable = [
        'status',
        'guru_id',
        'mapel12_id',
    ];
    public function guru()
    {
        return $this->belongsTo(guru::class, 'guru_id');
    }

    public function mapel12()
    {
        return $this->belongsTo(mapel12::class, 'mapel12_id');
    }

}
