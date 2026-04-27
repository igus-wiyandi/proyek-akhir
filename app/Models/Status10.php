<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Guru;
use App\Models\Mapel;
class Status10 extends Model
{
    use HasFactory;
    protected $table = 'status10';

    protected $fillable = [
        'status',
        'guru_id',
        'mapel_id',
    ];
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'mapel_id');
    }

}
