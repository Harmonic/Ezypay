<?php

namespace harmonic\Ezypay\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MetaData extends JsonResource
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
            'identifierType' => $this->resource['identifierType'],
            'identifierID' => $this->resource['identifierID'],
        ];
    }
}
