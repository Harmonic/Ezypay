<?php

namespace harmonic\Ezypay\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Settlement extends JsonResource
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
            'fileId' => $this->resource['fileId'],
            'createdOn' => $this->resource['createdOn'],
            'documentType' => $this->resource['documentType'],
        ];
    }
}
