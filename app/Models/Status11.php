<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Guru;
use App\Models\MapelKelas11;
class Status11 extends Model
{
    use HasFactory;
    protected $table = 'status11';

    protected $fillable = [
        'status',
        'guru_id',
        'mapel11_id',
    ];
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    public function mapel11()
    {
        return $this->belongsTo(MapelKelas11::class, 'mapel11_id');
    }

}
