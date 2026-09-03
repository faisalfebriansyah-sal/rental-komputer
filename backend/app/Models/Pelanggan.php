<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'no_hp',
    ];

    public function sesiRentals()
    {
        return $this->hasMany(Sesi_rental::class, 'pelanggan_id');
    }
}
