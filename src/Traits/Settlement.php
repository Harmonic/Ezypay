<?php

namespace harmonic\Ezypay\Traits;

trait Settlement {
    /**
     * Get a list of Settlements from Ezypay server
     *
     * @param boolean $fetchAll
     * @param string $from
     * @param string $until
     * @param integer $limit
     * @param integer $cursor
     * @return Object Settlements
     */
    public function getSettlements(bool $fetchAll = false, string $from = null, string $until = null, int $limit = null, int $cursor = null) {
        $filters = [
            'from' => $from,
            'until' => $until,
            'limit' => $limit,
            'cursor' => $cursor
        ];

        return $this->paginate('billing/settlements', $filters, $fetchAll);
    }

    /**
     * Group Settlement Report by Accounting Code
     *
     * @param string $dateFrom
     * @param string $dateTo
     * @param string $documentType
     * @param array $merchantIds
     * @return void
     */
    public function groupSettlementReportByAccountingCode(string $dateFrom = null, string $dateTo = null, string $documentType = null, array $merchantIds = []) {
        $data = [
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'documentType' => $documentType,
            'merchantIds' => $merchantIds
        ];

        $response = $this->request('POST', 'billing/settlements/groupedbyaccountingcode/file', $data);
        return \harmonic\Ezypay\Resources\Settlement::make($response)->resolve();
    }

    /**
     * Group Settlement Report by Transaction Status
     *
     * @param string $dateFrom
     * @param string $dateTo
     * @param string $documentType
     * @param array $merchantIds
     * @return void
     */
    public function groupSettlementReportByTransactionStatus(string $dateFrom = null, string $dateTo = null, string $documentType = null, array $merchantIds = []) {
        $data = [
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'documentType' => $documentType,
            'merchantIds' => $merchantIds
        ];

        $response = $this->request('POST', 'billing/settlements/groupedbytransactionstatus/file', $data);
        return \harmonic\Ezypay\Resources\Settlement::make($response)->resolve();
    }

    /**
     * Create a settlement report file
     *
     * @param string $settlementID The ID of the settlement report (from getSettlements)
     * @param string $type Should be 'summary_report' (default) or 'detail_report'
     * @return void
     */
    public function createSettlementFile(string $settlementID, string $type = 'summary_report') {
        $data = [
            'documentType' => $type
        ];

        $response = $this->request('POST', 'billing/settlements/' . $settlementID . '/file', $data);
        return \harmonic\Ezypay\Resources\Settlement::make($response)->resolve();
    }

    /**
     * Get a link to a settlement file
     *
     * @param string $settlementField The settlement field ID from calling createSettlementFile
     * @return void
     */
    public function getSettlementFileURL(string $settlementField) {
        $response = $this->request('GET', 'files/' . $settlementField);
        return \harmonic\Ezypay\Resources\SettlementFile::make($response)->resolve();
    }
}
