<?php

namespace harmonic\Ezypay\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Merchant extends JsonResource
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
            'number' => $this->resource['number'],
            'legalName' => $this->resource['legalName'],
            'name' => $this->resource['name'],
            'address' => Address::make($this->resource['address'])->resolve(),
            'countryCode' => $this->resource['countryCode'],
            'email' => $this->resource['email'],
            'phoneNumber' => $this->resource['phoneNumber'],
            'billing' => $this->resource['billing'],
            'settlement' => $this->resource['settlement'],
            'status' => $this->resource['status'],
            'businessRegistrationNumber' => $this->resource['businessRegistrationNumber'],
            'taxId' => $this->resource['taxId'],
            'categoryCode' => $this->resource['categoryCode'],
            'website' => $this->resource['website'],
            'description' => $this->resource['description'],
            'referenceCode' => $this->resource['referenceCode'],
            'settlementSchedule' => $this->resource['settlementSchedule'],
            'settlementPayoutMethod' => $this->resource['settlementPayoutMethod'],
            'acceptedPaymentMethods' => $this->resource['acceptedPaymentMethods'],
            'failedPaymentHandling' => $this->resource['failedPaymentHandling'],
            'paymentReactivationHandling' => $this->resource['paymentReactivationHandling'],
            'tax' => Tax::make($this->resource['tax'])->resolve(),
            'notifications' => $this->resource['notifications'],
            'metadata' => $this->resource['metadata'],
            'type' => $this->resource['type'],
            'feePricingId' => $this->resource['feePricingId'],
            'documentNumberSettings' => $this->resource['documentNumberSettings'],
        ];
    }
}
