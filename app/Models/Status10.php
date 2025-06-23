<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\guru;
use App\Models\mapel;
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
        return $this->belongsTo(guru::class, 'guru_id');
    }

    public function mapel()
    {
        return $this->belongsTo(mapel::class, 'mapel_id');
    }

}
