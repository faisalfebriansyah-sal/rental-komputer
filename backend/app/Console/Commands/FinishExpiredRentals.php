<?php

namespace App\Console\Commands;

use App\Events\RentalUpdated;
use App\Models\Sesi_rental;
use App\Models\Perangkat;
use Illuminate\Console\Command;

class FinishExpiredRentals extends Command
{
    protected $signature = 'rental:finish-expired';

    protected $description = 'Menyelesaikan sesi rental yang sudah melewati waktu selesai';

    public function handle()
    {
        $rentals = Sesi_rental::where('status', 'aktif')
            ->whereNotNull('waktu_selesai')
            ->where('waktu_selesai', '<=', now())
            ->get();

        foreach ($rentals as $rental) {
            $rental->update([
                'status' => 'selesai',
            ]);

            $perangkat = Perangkat::find($rental->perangkat_id);

            if ($perangkat) {
                $perangkat->update([
                    'status' => 'tersedia',
                ]);
            }

            event(new RentalUpdated($rental));
        }

        $this->info("{$rentals->count()} rental selesai diproses.");

        return Command::SUCCESS;
    }
}