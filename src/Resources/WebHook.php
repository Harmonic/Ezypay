<?php

namespace harmonic\Ezypay\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WebHook extends JsonResource
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
            'createdOn' => $this->resource['createdOn'],
            'url' => $this->resource['url'],
            'eventTypes' => $this->resource['eventTypes'],
        ];
    }
}
