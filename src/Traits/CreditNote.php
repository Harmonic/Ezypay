<?php

namespace harmonic\Ezypay\Traits;

use Symfony\Component\Routing\Exception\InvalidParameterException;

trait CreditNote {
    /**
     * Get credit notes
     *
     * @param string $customerId
     * @param string $subscriptionId
     * @param string $invoiceId
     * @param bool $fetchAll
     * @param string $status
     * @param string $reason
     * @param string $from
     * @param string $until
     * @param integer $limit
     * @param integer $cursor
     * @return array
     */
    public function getCreditNotes(string $customerId, string $subscriptionId = null, string $invoiceId = null, bool $fetchAll = false, string $status = null, string $reason = null, string $from = null, string $until = null, int $limit = null, int $cursor = null) {
        $filters = [
            'customerId' => $customerId,
            'subscriptionId' => $subscriptionId,
            'invoiceId' => $invoiceId,
            'status' => $status,
            'reason' => $reason,
            'from' => $from,
            'until' => $until,
            'limit' => $limit,
            'cursor' => $cursor
        ];

        return $this->paginate('billing/creditnotes', $filters, $fetchAll);
    }

    /**
     * Get a specific credit note
     *
     * @param string $creditNoteId
     * @return Object
     */
    public function getCreditNote(string $creditNoteId) {
        $response = $this->request('GET', 'billing/creditnotes/' . $creditNoteId);

        return \harmonic\Ezypay\Resources\CreditNote::make($response)->resolve();
    }
}
