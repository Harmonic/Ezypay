<?php

namespace harmonic\Ezypay\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Plan extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $meta = null;

        if (! empty($this->resource['metadata'])) {
            $meta = MetaData::make($this->resource['metadata'])->resolve();
        }

        return [
            'id' => $this->resource['id'],
            'status' => $this->resource['status'],
            'name' => $this->resource['name'],
            'memo' => $this->resource['memo'],
            'accountingCode' => $this->resource['accountingCode'],
            'amount' => Amount::make($this->resource['amount'])->resolve(),
            'tax' => Tax::make($this->resource['tax'])->resolve(),
            'setupPayments' => $this->resource['setupPayments'] ?? null,
            'intervalUnit' => $this->resource['intervalUnit'],
            'interval' => $this->resource['interval'],
            'billingStart' => $this->resource['billingStart'],
            'billingStartValue' => $this->resource['billingStartValue'],
            'billingEnd' => $this->resource['billingEnd'],
            'billingEndValue' => $this->resource['billingEndValue'],
            'firstBilling' => $this->resource['firstBilling'],
            'recurringBillingDay' => $this->resource['recurringBillingDay'],
            'failedPaymentHandling' => $this->resource['failedPaymentHandling'],
            'metadata' => $meta,
            'createdOn' => $this->resource['createdOn'],
        ];
    }
}
