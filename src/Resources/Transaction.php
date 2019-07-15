<?php

namespace harmonic\Ezypay\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Transaction extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource['id'],
            'number' => $this->resource['number'] ?? null,
            'status' => $this->resource['status'],
            'createdOn' => $this->resource['createdOn'],
            'failedOn' => $this->resource['failedOn'] ?? null,
            'amount' => Amount::make($this->resource['amount'])->resolve(),
            'type' => $this->resource['type'],
            'source' => $this->resource['source'],
            'paymentMethodType' => $this->resource['paymentMethodType'],
            'paymentMethodDescription' => $this->resource['paymentMethodDescription'],
            'failedPaymentReason' => $this->resource['failedPaymentReason'],
            'paymentProviderResponse' => $this->resource['paymentProviderResponse'],
            'document' => $this->resource['document'],
            'sender' => $this->resource['sender'],
            'receiver' => $this->resource['receiver'],
            'channel' => $this->resource['channel'],
        ];
    }
}
