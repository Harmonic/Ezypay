<?php

namespace harmonic\Ezypay\Traits;

use Symfony\Component\Routing\Exception\InvalidParameterException;

trait FutureInvoice {
    /**
     * Create Future Invoice
     *
     * @param string $subscriptionId
     * @param string $cycleStartDate
     * @param string $paymentMethodType
     * @return Object Invoice
     */
    public function createFutureInvoice(string $subscriptionId, string $cycleStartDate, string $paymentMethodType) {
        $data = [
            'subscriptionId' => $subscriptionId,
            'cycleStartDate' => $cycleStartDate,
            'paymentMethodType' => $paymentMethodType
        ];

        $invoice = $this->request(
            'POST',
            'billing/futureinvoices/recordpayment',
            $data
        );

        return $invoice;
    }

    /**
     * Update Future Invoice
     *
     * @param string $subscriptionId
     * @param string $cycleStartDate
     * @param string $paymentMethodType
     * @return Object Invoice
     */
    public function updateFutureInvoice(string $subscriptionId, string $cycleStartDate, string $date, array $items = []) {
        $data = [
            'subscriptionId' => $subscriptionId,
            'cycleStartDate' => $cycleStartDate,
            'date' => $date,
            'items' => $items
        ];

        $invoice = $this->request('PUT', 'billing/futureinvoices', $data);
        return $invoice;
    }

    /**
     * Delete Future Invoice
     *
     * @param string $subscriptionId
     * @param string $cycleStartDate
     * @return $response entityId bool deleted
     */
    public function deleteFutureInvoice(string $subscriptionId, string $cycleStartDate) {
        $data = [
            'subscriptionId' => $subscriptionId,
            'cycleStartDate' => $cycleStartDate
        ];

        $response = $this->request(
            'DELETE',
            'billing/futureinvoices',
            $data,
            true
        );
        return $response;
    }

    /**
     * Get a list of Future Invoice from Ezypay server
     *
     * @param string $subscriptionId
     * @param string $customerId
     * @param integer $limit
     * @param string $from
     * @param string $until
     * @param bool $fetchAll Get all pages of results
     * @return array Objects
     */
    public function getFutureInvoice(string $subscriptionId, string $customerId, string $from, string $until, int $limit = null, bool $fetchAll = false) {
        $filters = [
            'subscriptionId' => $subscriptionId,
            'customerId' => $customerId,
            'from' => $from,
            'until' => $until,
            'limit' => $limit
        ];

        return $this->paginate('billing/futureinvoices', $filters, $fetchAll);
    }
}
