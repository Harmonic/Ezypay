<?php

namespace harmonic\Ezypay\Traits;

trait Plan {
    /**
     * Create a new plan in Ezypay
     *
     * @codeCoverageIgnore // only used once to set plans in API.
     * @param String $name
     * @param String $accountingCode
     * @param Float $taxInclusiveAmt
     * @param Float $taxRate
     * @param String $intervalUnit
     * @param Integer $interval
     * @param String $billingStart
     * @param String $billingEnd
     * @param String $firstBilling
     * @param [type] $metadata
     * @param String $memo
     * @return void
     */
    public function createPlan(string $name, string $accountingCode, float $taxInclusiveAmt, string $status = 'active', float $taxRate = 10.00, string $intervalUnit = 'month', int $interval = 1, string $billingStart = 'day_of_month', string $billingEnd = 'ongoing', string $firstBilling = 'prorate', $metadata = null, string $memo = '', string $billingStartValue = '1') {
        $data = [
            'name' => $name,
            'memo' => $memo,
            'accountingCode' => $accountingCode,
            'amount' => [
                'currency' => $this->currency,
                'value' => $taxInclusiveAmt
            ],
            'status' => strtoupper($status),
            'tax' => [
                'rate' => $taxRate
            ],
            'intervalUnit' => $intervalUnit,
            'interval' => $interval,
            'billingStart' => $billingStart,
            'billingEnd' => $billingEnd,
            'firstBilling' => $firstBilling,
            'metadata' => $metadata,
            'billingStartValue' => $billingStartValue,
        ];

        $plan = $this->request('POST', 'billing/plans', $data);

        return $plan;
    }

    /**
     * Get a list of Plans from Ezypay server
     *
     * @param integer $limit
     * @param integer $cursor
     * @param string $name
     * @param string $status
     * @return array Object Plan
     */
    public function getPlans(bool $fetchAll = false, int $limit = null, int $cursor = null, string $name = null, string $status = null) {
        $filters = [
            'limit' => $limit,
            'cursor' => $cursor,
            'name' => $name,
            'status' => $status
        ];

        return $this->paginate('billing/plans', $filters, $fetchAll);
    }

    /**
     * Update a plan
     *
     * @param string $planId
     * @param string $name
     * @param string $accountingCode
     * @param float $taxInclusiveAmt
     * @param string $status
     * @param float $taxRate
     * @param string $intervalUnit
     * @param integer $interval
     * @param string $billingStart
     * @param string $billingEnd
     * @param string $firstBilling
     * @param [type] $metadata
     * @param string $memo
     * @param string $billingStartValue
     * @return Object Ezypay Plan
     */
    public function updatePlan(string $planId, string $name = null, string $accountingCode = null, float $taxInclusiveAmt = null, string $status = 'active', float $taxRate = 10.00, string $intervalUnit = 'month', int $interval = 1, string $billingStart = 'day_of_month', string $billingEnd = 'ongoing', string $firstBilling = 'prorate', $metadata = null, string $memo = '', string $billingStartValue = '1') {
        $data = [];
        if ($name !== null) {
            $data['name'] = $name;
        }
        if ($accountingCode !== null) {
            $data['accountingCode'] = $accountingCode;
        }
        if ($taxInclusiveAmt !== null) {
            $data['amount'] = [
                'currency' => $this->currency,
                'value' => $taxInclusiveAmt
            ];
        }
        if ($status !== null) {
            $data['status'] = strtolower($status);
        }
        if ($taxRate !== null) {
            $data['tax'] = [
                'rate' => $taxRate
            ];
        }

        if ($intervalUnit !== null) {
            $data['intervalUnit'] = strtoupper($intervalUnit);
        }
        if ($interval !== null) {
            $data['interval'] = $interval;
        }
        if ($billingStart !== null) {
            $data['billingStart'] = strtoupper($billingStart);
        }
        if ($billingEnd !== null) {
            $data['billingEnd'] = strtoupper($billingEnd);
        }
        if ($firstBilling !== null) {
            $data['firstBilling'] = strtoupper($firstBilling);
        }
        if ($metadata !== null) {
            $data['metadata'] = $metadata;
        }
        if ($billingStartValue !== null) {
            $data['billingStartValue'] = $billingStartValue;
        }
        //'memo' => $memo, //TODO: A blank memo causes issue, ignored for now

        $response = $this->request('PUT', 'billing/plans/' . $planId, $data);

        return \harmonic\Ezypay\Resources\Plan::make($response)->resolve();
    }

    /**
     * Get a specific Plan
     *
     * @param string $planId
     * @return Object Plan
     */
    public function getPlan(string $planId) {
        $response = $this->request('GET', 'billing/plans/' . $planId);

        return \harmonic\Ezypay\Resources\Plan::make($response)->resolve();
    }
}
