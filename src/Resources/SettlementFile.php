<?php

namespace harmonic\Ezypay\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SettlementFile extends JsonResource
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
            'url' => $this->resource['url'],
            'type' => $this->resource['type'],
            'status' => $this->resource['status'],
            'name' => $this->resource['name'],
            'purpose' => $this->resource['purpose'],
            'createdBy' => $this->resource['createdBy'],
            'createdOn' => $this->resource['createdOn'],
        ];
    }
}
