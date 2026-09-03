<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perangkat extends Model
{
    use HasFactory;

    protected $fillable = [
        'jenis_id',
        'name',
        'status',
    ];

    public function jenisPerangkat()
    {
        return $this->belongsTo(Jenis_perangkat::class, 'jenis_id');
    }

    public function sesiRentals()
    {
        return $this->hasMany(Sesi_rental::class, 'perangkat_id');
    }
}
