<?php

namespace harmonic\Ezypay\Traits;

use harmonic\Enums\InvoiceStatus;
use Symfony\Component\Routing\Exception\InvalidParameterException;

trait Invoice {
    /**
     * Get a list of invoices from Ezypay server
     *
     * @param bool $fetchAll Get all pages of results
     * @param string $customerId Filter by unique identifier of the customer.
     * @param string $subscriptionId Filter by unique identifier of the subscription.
     * @param string $status Filter by invoice status. Supported values are paid, processing, past_due, refunded and written_off
     * @param string $from Start date for range of invoices to view. Filter by date defined in the invoice date
     * @param string $until End date for range of invoices to view. Filter by date defined in the invoice date
     * @param integer $limit Apply a limit to the number of objects to be returned. Supported limit range is 1 to 100. Defaults to 10.
     * @param integer $cursor An cursor for use in pagination. By specifying the number of objects to skip, you are able to fetch the next page of objects. For example, if you make a list request with limit=10 and cursor=10, you will retrieve objects 11-20 across the full list.
     * @return array
     */
    public function getInvoices(bool $fetchAll = false, string $customerId = null, string $subscriptionId = null, int $status = null, string $from = null, string $until = null, int $limit = null, int $cursor = null) {
        if ($status !== null && !InvoiceStatus::hasValue($status)) {
            throw new InvalidParameterException("Status must be a valid number from harmonic\Enums\InvoiceStatus");
        }

        $filters = [
            'customerId' => $customerId,
            'subscriptionId' => $subscriptionId,
            'status' => InvoiceStatus::getKey($status),
            'from' => $from,
            'until' => $until,
            'limit' => $limit,
            'cursor' => $cursor
        ];

        return $this->paginate('billing/invoices', $filters, $fetchAll);
    }

    /**
     * Create Invoice
     *
     * @param string $customerId
     * @param array $items
     * @param string $paymentMethodToken
     * @param string $memo
     * @param boolean $autoPayment
     * @param string $scheduledPaymentDate
     * @return Object Invoice
     */
    public function createInvoice(string $customerId, array $items, string $paymentMethodToken = null, string $memo = null, bool $autoPayment = true, string $scheduledPaymentDate = null) {
        $data = [
            'customerId' => $customerId,
            'items' => $items,
            'paymentMethodToken' => $paymentMethodToken,
            'memo' => $memo,
            'autoPayment' => $autoPayment,
            'scheduledPaymentDate' => $scheduledPaymentDate
        ];

        $invoice = $this->request('POST', 'billing/invoices', $data);
        return $invoice;
    }

    /**
     * Get a specific invoice
     *
     * @param string $invoiceId
     * @return Object Invoice
     */
    public function getInvoice(string $invoiceId) {
        $response = $this->request('GET', 'billing/invoices/' . $invoiceId);
        return \harmonic\Ezypay\Resources\Invoice::make($response)->resolve();
    }

    /**
     * Update a invoice
     *
     * @param string $invoiceId
     * @param array $items
     * @param string $paymentMethodToken
     * @param object $scheduledPaymentDate
     * @return Object Invoice
     */
    public function updateInvoice(string $invoiceId, array $items = null, string $paymentMethodToken = null, \Carbon\Carbon $scheduledPaymentDate = null) {
        $data = [];

        if (!empty($items)) {
            $data['items'] = $items;
        }

        if (!empty($paymentMethodToken)) {
            $data['paymentMethodToken'] = $paymentMethodToken;
        }

        if (!empty($scheduledPaymentDate)) {
            $data['scheduledPaymentDate'] = $scheduledPaymentDate->toDateString();
        }

        $invoice = $this->request('PUT', 'billing/invoices/' . $invoiceId, $data);
        return $invoice;
    }

    /**
     * Record an external payment for an invoice
     *
     * @param string $invoiceId
     * @param string $paymentMethodType
     * @return Object Invoice
     */
    public function recordExternalPayment(string $invoiceId, string $paymentMethodType = null) {
        $data = ['paymentMethodType' => $paymentMethodType];

        $invoice = $this->request('POST', 'billing/invoices/' . $invoiceId . '/recordpayment', $data);
        return $invoice;
    }

    /**
     * Refund an invoice
     *
     * @param string $invoiceId
     * @param string $amountCurrency
     * @param integer $amountValue
     * @param array $items
     * @return Object Invoice
     */
    public function refundInvoice(string $invoiceId, string $amountCurrency, int $amountValue, array $items = []) {
        $data = [
            'amount' => [
                'currency' => $amountCurrency,
                'value' => $amountValue
            ],
            'items' => $items
        ];

        $invoice = $this->request('PUT', 'billing/invoices/' . $invoiceId . '/refund', $data);
        return $invoice;
    }

    /**
     * Retry a payment on an invoice
     *
     * @param string $invoiceId
     * @param bool $oneOff
     * @param string $paymentMethodToken
     * @return Object Invoice
     */
    public function retryPayment(string $invoiceId, bool $oneOff = false, string $paymentMethodToken = null) {
        $data = [
            'oneOff' => $oneOff,
            'paymentMethodToken' => $paymentMethodToken
        ];

        $invoice = $this->request(
            'POST',
            'billing/invoices/' . $invoiceId . '/retrypayment',
            $data
        );

        return $invoice;
    }

    /**
     * Write off an invoice
     *
     * @param string $invoiceId
     * @param array $data body params see https://developer.ezypay.com/reference#writeoffpaymentusingpost
     * @return Object Invoice
     */
    public function writeOffAnInvoice(string $invoiceId) {
        /**
         * @note array('id'=>null) is dummy data only as ezypay throws GuzzleHttp\Exception\ClientException:
         * Client error: `POST https://api-sandbox.ezypay.com/v2/billing/invoices/b2db7762-c514-49ea-b449-55ff0e1d2f09/writeoff?id=1` resulted in a `400 Bad Request` response:
         * {"type":"invalid_request_error","code":null,"message":"Required request body is missing: public com.ezypay.billing.domai (truncated...)
         */
        $data = ['id' => null];

        return $this->request('POST', 'billing/invoices/' . $invoiceId . '/writeoff', $data);
    }
}
