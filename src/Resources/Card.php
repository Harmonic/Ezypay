<?php

namespace harmonic\Ezypay\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Card extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request) {
        return [
            'first6' => $this->resource['first6'],
            'last4' => $this->resource['last4'],
            'accountHolderName' => $this->resource['accountHolderName'],
            'type' => $this->resource['type'],
            'expiryMonth' => $this->resource['expiryMonth'],
            'expiryYear' => $this->resource['expiryYear']
        ];
    }
}
