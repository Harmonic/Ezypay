<?php

namespace harmonic\Ezypay\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Bank extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request) {
        $bankAddress = null;
        if (isset($this->resource['bankAddress'])) {
            $bankAddress = Address::make($this->resource['bankAddress'])->resolve();
        }

        return [
            'accountHolderName' => $this->resource['accountHolderName'],
            'last4' => $this->resource['last4'],
            'bankNumber' => $this->resource['bankNumber'],
            'branchNumber' => $this->resource['branchNumber'],
            'suffixNumber' => $this->resource['suffixNumber'],
            'countryCode' => $this->resource['countryCode'] ?? null,
            'bankTransferType' => $this->resource['bankTransferType'] ?? 'local',
            'iban' => $this->resource['iban'] ?? null,
            'swiftBic' => $this->resource['swiftBic'] ?? null,
            'currency' => $this->resource['currency'] ?? null,
            'bankName' => $this->resource['bankName'] ?? null,
            'bankAddress' => $bankAddress,
            'routingCode' => $this->resource['routingCode'] ?? null,
        ];
    }
}
