<?php

namespace harmonic\Ezypay\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentMethod extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $bank = null;
        $card = null;

        if (! empty($this->resource['bank'])) {
            $bank = Bank::make($this->resource['bank'])->resolve();
        }
        if (! empty($this->resource['card'])) {
            $card = Card::make($this->resource['card'])->resolve();
        }

        return [
            'paymentMethodToken' => $this->resource['paymentMethodToken'],
            'customerId' => $this->resource['customerId'],
            'type' => $this->resource['type'],
            'bank' => $bank,
            'card' => $card,
            'invalidReason' => $this->resource['invalidReason'] ?? null,
            'lastUsedOn' => $this->resource['lastUsedOn'],
            'valid' => $this->resource['valid'],
            'primary' => $this->resource['primary'],
        ];
    }
}
