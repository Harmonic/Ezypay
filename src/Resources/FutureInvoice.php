<?php

namespace harmonic\Ezypay\Resources;

use harmonic\Ezypay\Resources\Item;
use harmonic\Ezypay\Resources\Amount;
use Illuminate\Http\Resources\Json\JsonResource;

class FutureInvoice extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request) {
        return [
            'subscriptionId' => $this->resource['subscriptionId'] ?? null,
            'date' => $this->resource['date'],
            'cycleStartDate' => $this->resource['cycleStartDate'],
            'cycleEndDate' => $this->resource['cycleEndDate'] ?? null,
            'items' => ItemCollection::make(collect($this->resource['items']))->resolve(),
            'amount' => Amount::make($this->resource['amount'])->resolve(),
            'totalTax' => Amount::make($this->resource['totalTax'])->resolve(),
        ];
    }
}
