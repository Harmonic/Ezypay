<?php

namespace harmonic\Ezypay\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CreditNote extends JsonResource
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
            'invoiceId' => $this->resource['invoiceId'],
            'documentNumber' => $this->resource['documentNumber'],
            'date' => $this->resource['date'],
            'status' => $this->resource['status'],
            'items' => $this->resource['items'],
            'amount' => Amount::make($this->resource['amount'])->resolve(),
            'totalTax' => Amount::make($this->resource['totalTax'])->resolve(),
            'reason' => $this->resource['reason'],
            'customerId' => $this->resource['customerId'],
            'paymentMethodToken' => $this->resource['paymentMethodToken'],
        ];
    }
}
