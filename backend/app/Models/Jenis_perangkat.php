<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jenis_perangkat extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'harga_per_jam',
    ];

    protected $casts = [
        'harga_per_jam' => 'decimal:2',
    ];

    public function perangkats()
    {
        return $this->hasMany(Perangkat::class, 'jenis_id');
    }
}
