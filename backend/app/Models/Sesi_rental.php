<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sesi_rental extends Model
{
    use HasFactory;

    protected $fillable = [
        'pelanggan_id',
        'perangkat_id',
        'kode_sesi',
        'durasi',
        'harga',
        'status',
        'waktu_mulai',
        'waktu_selesai',
    ];

    protected $casts = [
        'durasi' => 'integer',
        'harga' => 'decimal:2',
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    public function perangkat()
    {
        return $this->belongsTo(Perangkat::class, 'perangkat_id');
    }

    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class, 'sesi_id');
    }
}
