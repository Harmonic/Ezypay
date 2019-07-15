<?php

namespace harmonic\Ezypay\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use harmonic\Ezypay\Resources\PaymentMethod;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class PaymentMethodSaved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $paymentMethod;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(PaymentMethod $paymentMethod)
    {
        $this->paymentMethod = $paymentMethod->resolve();
    }

    /**
     * Get the channels the event should broadcast on.
     * @codeCoverageIgnore
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
