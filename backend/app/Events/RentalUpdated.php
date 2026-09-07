<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RentalUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $rental;

    public function __construct($rental = null)
    {
        $this->rental = $rental;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('rentals'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'rental.updated';
    }
}
