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
        $this->assertTrue(array_key_exists('data', $transactions));
        $this->assertTrue(array_key_exists('id', $transactions['data'][0]));
        $this->assertTrue(array_key_exists('createdOn', $transactions['data'][0]));
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

        $transactions = Ezypay::getTransactions(false, null, null, null, 100, null, null, null, 'PROCESSING');

        // Assert
        $this->assertTrue(array_key_exists('id', $transactions['data'][0]));
        $this->assertEquals('PROCESSING', $transactions['data'][0]['status']);
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

        $transaction = Ezypay::getTransaction($transactions['data'][0]['id']);

        // Assert
        $this->assertTrue(array_key_exists('id', $transaction));
        $this->assertTrue(array_key_exists('createdOn', $transaction));
        $this->assertEquals($transactions['data'][0]['id'], $transaction['id']);
    }
}
