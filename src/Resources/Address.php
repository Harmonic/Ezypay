<?php

namespace harmonic\Ezypay\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Address extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request) {
        return [
            'address1' => $this->resource['address1'],
            'address2' => $this->resource['address2'],
            'postalCode' => $this->resource['postalCode'],
            'state' => $this->resource['state'],
            'countryCode' => $this->resource['countryCode'],
            'city' => $this->resource['city'],
        ];
    }
}
