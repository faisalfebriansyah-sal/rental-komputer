<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'sesi_id',
        'jumlah',
        'status',
        'waktu_bayar',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'waktu_bayar' => 'datetime',
    ];

    public function sesiRental()
    {
        return $this->belongsTo(Sesi_rental::class, 'sesi_id');
    }
}
