<?php

namespace harmonic\Ezypay\Tests;

use harmonic\Ezypay\Tests\EzypayBaseTest;
use harmonic\Ezypay\Facades\Ezypay;
use Illuminate\Support\Carbon;
use harmonic\Models\Ezypay as EzypayModel;

class TransactionTest extends EzypayBaseTest {
    /**
     * Can get a list of invoices
     *
     * @test
     * @return void
     */
    public function getAListOfTransactions() {
        // Arrange
        // Act
        $transactions = Ezypay::getTransactions();

        // Assert
        $this->assertTrue(array_key_exists('id', $transactions[0]));
        $this->assertTrue(array_key_exists('createdOn', $transactions[0]));
    }

    /**
     * Should return all transactions filtered by status
     *
     * @test
     * @return void
     */
    public function getListOfTransactionsFilteredByStatus() {
        // Arrange
        // Act

        $transactions = Ezypay::getTransactions(false, null, null, null, 100, null, null, null, 'processing');

        // Assert
        $this->assertTrue(array_key_exists('id', $transactions[0]));
        $this->assertEquals('PROCESSING', $transactions[0]['status']);
    }

    /**
     * Can get specific transaction
     *
     * @test
     * @return void
     */
    public function getTransactionById() {
        // Arrange

        // Act
        $transactions = Ezypay::getTransactions();

        $transaction = Ezypay::getTransaction($transactions[0]['id']);

        // Assert
        $this->assertTrue(array_key_exists('id', $transaction));
        $this->assertTrue(array_key_exists('createdOn', $transaction));
        $this->assertEquals($transactions[0]['id'], $transaction['id']);
    }
}
