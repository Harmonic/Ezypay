<?php

namespace harmonic\Ezypay\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Customer extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $address = $this->resource['address'] ?? null;
        $metadata = $this->resource['metadata'] ?? null;

        if (! empty($address)) {
            $address = Address::make($address)->resolve();
        }
        if (! empty($metadata)) {
            $metadata = MetaData::make($metadata)->resolve();
        }

        return [
            'id' => $this->resource['id'],
            'email' => $this->resource['email'],
            'mobilePhone' => $this->resource['mobilePhone'] ?? null,
            'homePhone' => $this->resource['homePhone'] ?? null,
            'gender' => $this->resource['gender'] ?? null,
            'dateOfBirth' => $this->resource['dateOfBirth'] ?? null,
            'firstName' => $this->resource['firstName'],
            'lastName' => $this->resource['lastName'],
            'companyName' => $this->resource['companyName'] ?? null,
            'referenceCode' => $this->resource['referenceCode'] ?? null,
            'customerNumber' => $this->resource['number'] ?? null,
            'createdDate' => $this->resource['createdOn'] ?? null,
            'address' => $address,
            'metadata' => $metadata,
        ];
    }
}
