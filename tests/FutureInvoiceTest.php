<?php

namespace harmonic\Ezypay\Tests;

use Illuminate\Support\Carbon;
use harmonic\Ezypay\Facades\Ezypay;

class FutureInvoiceTest extends EzypayBaseTest
{
    /**
     * Can record external payment future invoice.
     *
     * @test
     * @return void
     */
    public function canRecordExternalPayment()
    {
        // Arrange
        $customer = Ezypay::createCustomer();
        $subscription = Ezypay::createSubscription($customer['id']);

        // Act
        $futureInvoices = Ezypay::createFutureInvoice($subscription['id'], $subscription['startDate'], 'cash');

        // Assert
        $this->assertNotNull($futureInvoices);
        $this->assertEquals($subscription['id'], $futureInvoices['subscriptionId']);
        $this->assertEquals('PAID', $futureInvoices['status']);
    }

    /**
     * Can get list of future invoices.
     *
     * @test
     * @return void
     */
    public function canGetListOfFutureInvoices()
    {
        // Arrange
        $subscription = Ezypay::getSubscription($this->faker->uuid);
        $startDate = Carbon::now()->addDays(30)->toDateString();
        $endDate = Carbon::now()->addDays(60)->toDateString();

        // Act
        $futureInvoices = Ezypay::getFutureInvoices(
            $subscription['id'],
            $subscription['customerId'],
            $startDate,
            $endDate,
            true
        );

        // Assert
        $this->assertNotNull($futureInvoices);
        $this->assertEquals($subscription['id'], $futureInvoices['data'][0]['subscriptionId']);

        $this->futureInvoices = $futureInvoices;
    }

    /**
     * Can record external payment future invoice.
     *
     * @test
     * @return void
     */
    public function canUpdateFutureInvoice()
    {
        // Arrange
        $futureInvoices = Ezypay::getFutureInvoices($this->faker->uuid, $this->faker->uuid, Carbon::now()->sub('1 month'), Carbon::now());

        // Act
        $invoice = $futureInvoices['data'][0];
        $futureInvoice = Ezypay::updateFutureInvoice($invoice['subscriptionId'], $invoice['cycleStartDate'], $invoice['cycleStartDate']);

        // Assert
        $this->assertNotNull($futureInvoice);
        $this->assertEquals($invoice['subscriptionId'], $futureInvoice['data'][0]['subscriptionId']);
        $this->assertEquals($invoice['cycleStartDate'], $futureInvoice['data'][0]['cycleStartDate']);
    }

    /**
     * Can delete future invoice.
     *
     * @test
     * @return void
     */
    public function canDeleteInvoice()
    {
        // Arrange
        $futureInvoices = Ezypay::getFutureInvoices($this->faker->uuid, $this->faker->uuid, Carbon::now()->sub('1 month'), Carbon::now());

        // Act
        $invoice = $futureInvoices['data'][0];
        $futureInvoice = Ezypay::deleteFutureInvoice($invoice['subscriptionId'], $invoice['cycleStartDate']);

        // Assert
        $this->assertEquals($futureInvoice['delete'], 'true');
    }
}
