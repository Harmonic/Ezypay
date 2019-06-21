<?php

namespace harmonic\Ezypay\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use harmonic\Ezypay\Resources\PaymentMethod;

class PaymentMethodSaved {
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $paymentMethod;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(PaymentMethod $paymentMethod) {
        $this->paymentMethod = $paymentMethod->resolve();
    }

    /**
     * Get the channels the event should broadcast on.
     * @codeCoverageIgnore
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn() {
        return new PrivateChannel('channel-name');
    }
}
