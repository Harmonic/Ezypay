<?php

namespace harmonic\Ezypay\Tests;

use harmonic\Ezypay\Tests\EzypayBaseTest;
use harmonic\Ezypay\Facades\Ezypay;
use Illuminate\Support\Carbon;
use harmonic\Models\Ezypay as EzypayModel;

class SettlementTest extends EzypayBaseTest {
    /**
     * Can get a list of Settlements
     *
     * @test
     * @return void
     */
    public function getAListOfSettlements() {
        // Arrange
        // Act
        $settlements = Ezypay::getSettlements();

        // Assert
        $this->assertNotNull($settlements);
    }

    /**
     * Can Group Settlement Report
     *
     * @test
     * @return void
     */
    public function canGroupSettlementReportByAccountingCode() {
        // Arrange
        $dateTo = Carbon::now()->toDateString();
        $dateFrom = Carbon::now()->subDays(30)->toDateString();

        // Act
        $settlementGroup = Ezypay::groupSettlementReportByAccountingCode($dateFrom, $dateTo);

        // Assert
        $this->assertNotNull($settlementGroup);
        $this->assertEquals('groupedby_accountingcode', $settlementGroup['documentType']);
    }

    /**
     * Group Settlement Report
     *
     * @test
     * @return void
     */
    public function canGroupSettlementReportByTransactionStatus() {
        // Arrange
        $dateTo = Carbon::now()->toDateString();
        $dateFrom = Carbon::now()->subDays(30)->toDateString();

        // Act
        $settlementGroup = Ezypay::groupSettlementReportByTransactionStatus($dateFrom, $dateTo);

        // Assert
        $this->assertNotNull($settlementGroup);
        $this->assertEquals('groupedby_transactionstatus', $settlementGroup['documentType']);
    }
}
