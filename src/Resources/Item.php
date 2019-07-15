<?php

namespace harmonic\Ezypay\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Item extends JsonResource
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
            'description' => $this->resource['description'],
            'amount' => Amount::make($this->resource['amount'])->resolve(),
            'tax' => Tax::make($this->resource['tax'])->resolve(),
            'id' => $this->resource['id'] ?? null, // null is valid on payment fees etc
            'type' => $this->resource['type'],
            'accountingCode' => $this->resource['accountingCode'] ?? null,
        ];
    }
}
