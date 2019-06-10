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

        // Assert
        $this->assertTrue(array_key_exists('id', $invoices[0]));
        $this->assertTrue(array_key_exists('documentNumber', $invoices[0]));
        $this->assertTrue(array_key_exists('date', $invoices[0]));
    }

    /**
     * Can create invoice
     *
     * @test
     * @return void
     */
    public function canCreateInvoice() {
        // Arrange
        // Act
        $invoice = Ezypay::createInvoice($this->ezypayCustomerID, [
            (object) [
                'description' => 'Share Link Bronze',
                'amount' => (object) [
                    'currency' => 'AUD',
                    'value' => 29.7
                ]
            ]
        ], $this->ezypayPaymentMethodToken);

        // Assert
        $this->assertTrue(array_key_exists('id', $invoice));
        $this->assertTrue(array_key_exists('documentNumber', $invoice));
        $this->assertTrue(array_key_exists('date', $invoice));

        $this->assertEquals($this->ezypayCustomerID, $invoice['customerId']);

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
        $invoiceList = Ezypay::getInvoices(false, null, null, InvoiceStatus::past_due, null, null, 1);

        // Act
        $invoice = Ezypay::recordExternalPayment($invoiceList[0]['id'], 'cash');

        // // Assert
        $this->assertTrue(array_key_exists('id', $invoice));
        $this->assertEquals('PAID', $invoice['status']);
    }

    /**
     * Can refund invoice
     *
     * @test
     * @return void
     */
    public function canRefundInvoice() {
        // Arrange
        $paidInvoice = Ezypay::getInvoices(false, $this->ezypayCustomerID, null, InvoiceStatus::paid, null, null);

        // Act
        $invoice = Ezypay::refundInvoice($paidInvoice[1]['id'], $paidInvoice[1]['amount']['currency'], $paidInvoice[1]['amount']['value']);

        // Assert
        $this->assertTrue(array_key_exists('id', $invoice));
        $this->assertEquals('PROCESSING', $invoice['status']);
    }

    /**
     * Can retry payment
     *
     * @test
     * @return void
     */
    public function canRetryPayment() {
        // Arrange
        $customer = $this->createTestCustomer();

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
            $this->ezypayPaymentMethodToken
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
        $invoiceList = Ezypay::getInvoices(false, null, null, InvoiceStatus::past_due, null, null, 1);

        // Act
        $invoice = Ezypay::writeOffAnInvoice($invoiceList[0]['id']);

        // Assert
        $this->assertEquals($invoiceList[0]['id'], $invoice['id']);
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
