<?php

namespace harmonic\Ezypay\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Subscription extends JsonResource
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
            'customerId' => $this->resource['customerId'],
            'planId' => $this->resource['planId'],
            'name' => $this->resource['name'],
            'status' => $this->resource['status'],
            'startDate' => $this->resource['startDate'],
            'endDate' => $this->resource['endDate'] ?? null,
            'paymentMethodToken' => $this->resource['paymentMethodToken'],
            'accountingCode' => $this->resource['accountingCode'],
            'amount' => Amount::make($this->resource['amount'])->resolve(),
            'tax' => Tax::make($this->resource['tax'])->resolve(),
            'nextBillingDate' => $this->resource['nextBillingDate'],
            'nextFutureInvoice' => FutureInvoice::make($this->resource['nextFutureInvoice'])->resolve(),
            'interval' => $this->resource['interval'],
            'intervalUnit' => $this->resource['intervalUnit'],
            'totalPaid' => Amount::make($this->resource['totalPaid'])->resolve(),
            'totalBillingCycles' => $this->resource['totalBillingCycles'],
            'remainingToPay' => $this->resource['remainingToPay'] ?? null,
            'remainingBillingCycles' => $this->resource['remainingBillingCycles'] ?? null,
            'endTargetAmount' => $this->resource['endTargetAmount'] ?? null,
            'endTargetBillingCycles' => $this->resource['endTargetBillingCycles'] ?? null,
            'cancelledDate' => $this->resource['cancelledDate'] ?? null,
            'failedPaymentHandling' => $this->resource['failedPaymentHandling'] ?? null,
            'failedAttemptsCount' => $this->resource['failedAttemptsCount'],
            'totalPastDue' => Amount::make($this->resource['totalPastDue'])->resolve(),
            'totalDiscounted' => Amount::make($this->resource['totalDiscounted'])->resolve(),
            'metadata' => $meta,
            'createdOn' => $this->resource['createdOn'],
            'autoPayment' => $this->resource['autoPayment'],
            'setupPayments' => $this->resource['setupPayments'] ?? null,
        ];
    }
}
