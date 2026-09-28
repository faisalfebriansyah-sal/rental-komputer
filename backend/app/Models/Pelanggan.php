<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'kode_member',
    ];

    protected static function booted(): void
    {
        static::creating(function (Pelanggan $pelanggan) {
            if (empty($pelanggan->kode_member)) {
                do {
                    $kode = 'MBR-' . random_int(100000, 999999);
                } while (self::where('kode_member', $kode)->exists());

                $pelanggan->kode_member = $kode;
            }
        });
    }

    public function sesiRentals()
    {
        return $this->hasMany(Sesi_rental::class, 'pelanggan_id');
    }
}
