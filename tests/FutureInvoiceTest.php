<?php

namespace harmonic\Ezypay\Tests;

use harmonic\Ezypay\Tests\EzypayBaseTest;
use harmonic\Ezypay\Facades\Ezypay;
use Illuminate\Support\Carbon;


class FutureInvoiceTest extends EzypayBaseTest
{
    

    /**
     * Can record external payment future invoice
     *
     * @test
     * @return void
     */
    public function canRecordExternalPayment()
    {
        // Arrange
        $customer = $this->createTestCustomer();

        $subscription = &$this->getSharedSubscription();
        $subscription = $this->createSubscription($customer);

        // Act
        $futureInvoices = Ezypay::createFutureInvoice($subscription['id'], $subscription['startDate'], 'cash');

        // Assert
        $this->assertNotNull($futureInvoices);
        $this->assertEquals($subscription['id'], $futureInvoices['subscriptionId']);
        $this->assertEquals('cash', $futureInvoices['paymentMethodType']);
        $this->assertEquals('PAID', $futureInvoices['status']);
    }

    /**
     * Can get list of future invoices
     *
     * @test
     * @return void
     */
    public function canGetListOfFutureInvoice()
    {
        // Arrange
        $subscription = &$this->getSharedSubscription();
        $startDate = Carbon::now()->addDays(30)->toDateString();
        $endDate = Carbon::now()->addDays(60)->toDateString();

        // Act

        $futureInvoices = Ezypay::getFutureInvoice(
            $subscription['id'],
            $subscription['customerId'],
            $startDate,
            $endDate,
            true
        );

        // Assert
        $this->assertNotNull($futureInvoices);
        $this->assertEquals($subscription['id'], $futureInvoices[0]['subscriptionId']);

        $this->futureInvoices = $futureInvoices;
    }

    /**
     * Can record external payment future invoice
     *
     * @test
     * @return void
     */
    public function canUpdateFutureInvoice()
    {
        // Arrange
        if (!isset($this->futureInvoices)) {
            $this->canGetListOfFutureInvoice();
        }
        $futureInvoiceList = $this->futureInvoices;

        // Act

        $futureInvoice = Ezypay::updateFutureInvoice($futureInvoiceList[0]['subscriptionId'], $futureInvoiceList[0]['cycleStartDate'], $futureInvoiceList[0]['cycleStartDate']);

        // Assert
        $this->assertNotNull($futureInvoice);
        $this->assertEquals($futureInvoiceList[0]['subscriptionId'], $futureInvoice['subscriptionId']);
    }

    /**
     * Can delete future invoice
     *
     * @test
     * @return void
     */
    public function canDeteleInvoice()
    {
        // Arrange
        if (!isset($this->futureInvoices)) {
            $this->canGetListOfFutureInvoice();
        }
        $futureInvoiceList = $this->futureInvoices;

        // Act

        $futureInvoice = Ezypay::deleteFutureInvoice($futureInvoiceList[0]['subscriptionId'], $futureInvoiceList[0]['cycleStartDate']);

        // Assert
        $this->assertTrue($futureInvoice['deleted']);
    }
}
