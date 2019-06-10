<?php

namespace harmonic\Ezypay\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Vault extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request) {
        $bank = null;
        $card = null;

        if (!empty($this->resource['bank'])) {
            $bank = Bank::make($this->resource['bank'])->resolve();
        }
        if (!empty($this->resource['card'])) {
            $card = Card::make($this->resource['card'])->resolve();
        }

        return [
            'type' => $this->resource['type'],
            'card' => $card,
            'bank' => $bank,
            'paymentMethodToken' => $this->resource['paymentMethodToken'],
        ];
    }
}
