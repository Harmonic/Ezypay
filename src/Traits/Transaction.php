<?php

namespace harmonic\Ezypay\Traits;

trait Transaction {
    /**
     * Get All Transactions
     *
     * @param string $fetchAll
     * @param string $transactionNumber
     * @param string $senderId
     * @param string $documentId
     * @param integer $limit
     * @param integer $cursor
     * @param string $from
     * @param string $until
     * @param string $status
     * @return array Object Transactions
     */
    public function getTransactions(bool $fetchAll = false, string $transactionNumber = null, string $senderId = null, string $documentId = null, int $limit = null, int $cursor = null, string $from = null, string $until = null, string $status = null) {
        $filters = [
            'transactionNumber' => $transactionNumber,
            'senderId' => $senderId,
            'documentId' => $documentId,
            'limit' => $limit,
            'cursor' => $cursor,
            'from' => $from,
            'until' => $until,
            'status' => $status
        ];

        return $this->paginate('billing/transactions/', $filters, $fetchAll);
    }

    /**
     * Retrieve a transaction
     *
     * @param string $transactionId
     * @return Object Transactions
     */
    public function getTransaction(string $transactionId) {
		$response = $this->request('GET', 'billing/transactions/' . $transactionId);
		return \harmonic\Ezypay\Resources\Transaction::make($response)->resolve();
    }
}
