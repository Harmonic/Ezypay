<?php

namespace harmonic\Ezypay\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Invoice extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request) {
        return [
            'id' => $this->resource['id'],
            'documentNumber' => $this->resource['documentNumber'],
            'date' => $this->resource['date'],
            'dueDate' => $this->resource['dueDate'],
            'scheduledPaymentDate' => $this->resource['scheduledPaymentDate'] ?? null,
            'status' => $this->resource['status'],
            'memo' => $this->resource['memo'] ?? null,
            'items' => ItemCollection::make(collect($this->resource['items']))->resolve(),
            'amount' => Amount::make($this->resource['amount'])->resolve(),
            'amountWithoutDiscount' => Amount::make($this->resource['amountWithoutDiscount'])->resolve(),
            'totalDiscounted' => Amount::make($this->resource['totalDiscounted'])->resolve(),
            'totalRefunded' => Amount::make($this->resource['totalRefunded'])->resolve(),
            'totalTax' => Amount::make($this->resource['totalTax'])->resolve(),
            'customerId' => $this->resource['customerId'],
            'subscriptionId' => $this->resource['subscriptionId'] ?? null,
            'subscriptionName' => $this->resource['subscriptionName'] ?? null,
            'paymentMethodToken' => $this->resource['paymentMethodToken'],
            'autoPayment' => $this->resource['autoPayment'],
            'createdOn' => $this->resource['createdOn'],
        ];
    }
}
