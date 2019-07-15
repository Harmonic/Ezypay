<?php

namespace harmonic\Ezypay\Tests;

use harmonic\Ezypay\Tests\EzypayBaseTest;
use harmonic\Ezypay\Facades\Ezypay;
use harmonic\Enums\InvoiceStatus;

class InvoiceTest extends EzypayBaseTest {

    /**
     * Can get a list of invoices
     *
     * @test
     * @return void
     */
    public function getAListOfInvoices() {
        // Arrange
        // Act
        $invoices = Ezypay::getInvoices(false, null, null, null, null, null, 1);
        $invoice = $invoices['data'][0];

        // Assert
        $this->assertEquals($invoices['paging']['totalCount'], 1);
        $this->assertTrue(array_key_exists('id', $invoice));
        $this->assertTrue(array_key_exists('documentNumber', $invoice));
        $this->assertTrue(array_key_exists('date', $invoice));
    }

    /**
     * Can create invoice
     *
     * @test
     * @return void
     */
    public function canCreateInvoice() {
        // Arrange
        $customerId = $this->faker->uuid;
        $paymentMethodToken = $this->faker->uuid;

        // Act
        $invoice = Ezypay::createInvoice(
            $customerId, 
            [
                (object) [
                    'description' => 'Share Link Bronze',
                    'amount' => (object) [
                        'currency' => 'AUD',
                        'value' => 29.7
                    ]
                ]
            ], 
            $paymentMethodToken);

        // Assert
        $this->assertTrue(array_key_exists('id', $invoice));
        $this->assertTrue(array_key_exists('documentNumber', $invoice));
        $this->assertTrue(array_key_exists('date', $invoice));

        $this->assertEquals($customerId, $invoice['customerId']);
        $this->assertEquals($paymentMethodToken, $invoice['paymentMethodToken']);

        $this->invoice = $invoice;

        return $invoice;
    }

    /**
     * Can get invoice by ID
     *
     * @test
     * @return void
     */
    public function canGetSpecificInvoiceById() {
        // Arrange
        if (!isset($this->invoice)) {
            $this->canCreateInvoice();
        }

        // Act

        $invoice = Ezypay::getInvoice($this->invoice['id']);

        // Assert
        $this->assertEquals($this->invoice['id'], $invoice['id']);

        $this->assertTrue(array_key_exists('id', $invoice));
        $this->assertTrue(array_key_exists('documentNumber', $invoice));
        $this->assertTrue(array_key_exists('date', $invoice));
    }

    /**
     * Can record external payment
     *
     * @test
     * @return void
     */
    public function canRecordExternalPayment() {
        // Arrange
        $invoiceList = Ezypay::getInvoices(false, "", "", "PROCESSING", "", "", 1);
        $invoiceResult = $invoiceList['data'][0];
        // Act
        $invoice = Ezypay::recordExternalPayment($invoiceResult['id'], 'cash');

        // // Assert
        $this->assertTrue(array_key_exists('id', $invoice));
        $this->assertEquals('PROCESSING', $invoice['status']);
    }

    /**
     * Can refund invoice
     *
     * @test
     * @return void
     */
    public function canRefundInvoice() {
        // Arrange
        $paidInvoices = Ezypay::getInvoices(false, $this->faker->uuid, null, "PAID", null, null);
        $paidInvoice = $paidInvoices['data'][0];

        // Act
        $invoice = Ezypay::refundInvoice($paidInvoice['id'], $paidInvoice['amount']['currency'], $paidInvoice['amount']['value']);

        // Assert
        $this->assertTrue(array_key_exists('id', $invoice));
        $this->assertEquals('PAID', $invoice['status']);
    }

    /**
     * Can retry payment
     *
     * @test
     * @return void
     */
    public function canRetryPayment() {
        // Arrange
        $customer = EzyPay::createCustomer();

        $createInvoice = Ezypay::createInvoice($customer['id'], [
            (object) [
                'description' => 'Share Link Bronze',
                'amount' => (object) [
                    'currency' => 'AUD',
                    'value' => 29.7
                ]
            ]
        ]);

        // Act
        $invoice = Ezypay::retryPayment(
            $createInvoice['id'],
            true,
            $this->faker->uuid
        );

        // Assert
        $this->assertTrue(array_key_exists('id', $invoice));
        $this->assertEquals('PROCESSING', $invoice['status']);
    }

    /**
     * Can write off invoice
     *
     * @test
     * @return void
     */
    public function canWriteOffInvoice() {
        // Arrange
        $invoiceList = Ezypay::getInvoices(false, null, null, "PAST_DUE", null, null, 1);

        // Act
        $invoice = Ezypay::writeOffAnInvoice($invoiceList['data'][0]['id']);

        // Assert
        $this->assertEquals($invoiceList['data'][0]['id'], $invoice['id']);
        $this->assertTrue(array_key_exists('id', $invoice));
        $this->assertEquals('WRITTEN_OFF', $invoice['status']);
    }

    /**
     * Can update invoice
     * @todo block with this error message
     * 1) Tests\Feature\InvoiceTest::canUpdateInvoice
     * GuzzleHttp\Exception\ClientException: Client error: `PUT https://api-sandbox.ezypay.com/v2/billing/invoices/f326f1c6-490d-42b1-ad2d-e687b4869c35` resulted in a `400 Bad Request` response:
     * {"type":"invalid_request_error","code":"payment_method_token_not_found","message":"Payment method with specified payment (truncated...)
     * @test
     * @return void
     */
    // public function canUpdateInvoice() {
    //     // Arrange
    //     $ezypay = $this->ezypay;
    //     $invoices = $ezypay->getInvoices(false, null, null, InvoiceStatus::past_due, null, null, 1);
    //     $startDate = Carbon::now()->addDays(30);

    //     // Act
    //     $invoice = $ezypay->updateInvoice(($invoices[0])['id'], null, null, $startDate);

    //     // Assert
    //     $this->assertTrue(true);
    //     $this->assertTrue(property_exists($invoice, 'documentNumber'));
    //     $this->assertTrue(property_exists($invoice, 'date'));
    // }
}
