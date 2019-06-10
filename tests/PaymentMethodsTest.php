<?php

namespace harmonic\Ezypay\Tests;

use harmonic\Ezypay\Tests\EzypayBaseTest;
use harmonic\Ezypay\Facades\Ezypay;
use harmonic\Models\Ezypay as EzypayModel;
use Illuminate\Foundation\Testing\WithFaker;


class PaymentMethodsTest extends EzypayBaseTest {
    use WithFaker;
    

    /**
     * Can get a list of customer payment methods
     *
     * @test
     * @return void
     */
    public function getAListOfPaymentMethods() {
        // Arrange
        // Act
        $paymentMethods = Ezypay::getPaymentMethods($this->ezypayCustomerID, false);

        // Assert
        $this->assertInternalType('array', $paymentMethods);
        $this->assertEquals($this->ezypayCustomerID, $paymentMethods['customerId']);
    }

    /**
     * Can relate a customer to a payment method
     *
     * @test
     * @return void
     */
    public function canCreatePaymentMethodForCustomer() {
        // Arrange
        $paymentMethodToken = $this->ezypayPaymentMethodToken;
        $customer = $this->createTestCustomer();
        // Act

        $paymentMethod = Ezypay::createPaymentMethod($customer['id'], $paymentMethodToken);

        // Assert
        $this->assertEquals($paymentMethodToken, $paymentMethod['paymentMethodToken']);
        $this->assertEquals($paymentMethod['primary'], true);

        $this->paymentMethod = $paymentMethod;

        return $paymentMethod;
    }

    /**
     * Can get customer primary payment method
     *
     * @test
     * @return Object PaymentMethod
     */
    public function getCustomerPrimaryPaymentMethod() {
        // Arrange
        if (!isset($this->paymentMethod)) {
            $this->canCreatePaymentMethodForCustomer();
        }
        $customerId = $this->paymentMethod['customerId'];

        // Act

        $paymentMethod = Ezypay::getPrimaryPaymentMethod($customerId);

        // Assert
        $this->assertTrue(array_key_exists('primary', $paymentMethod));
        $this->assertEquals(true, $paymentMethod['primary']);
        $this->assertEquals($paymentMethod['customerId'], $customerId);

        $this->primaryPaymentMethod = $paymentMethod;

        return $paymentMethod;
    }

    /**
     * Can get customer payment method by token
     *
     * @test
     * @return void
     */
    public function canGetPaymentMethodByToken() {
        // Act
        if (!isset($this->paymentMethod)) {
            $this->canCreatePaymentMethodForCustomer();
        }
        $samplePaymentMethod = $this->paymentMethod;

        $paymentMethod = Ezypay::getPaymentMethod($samplePaymentMethod['customerId'], $samplePaymentMethod['paymentMethodToken']);

        // Assert
        $this->assertTrue(array_key_exists('paymentMethodToken', $paymentMethod));
        $this->assertEquals($samplePaymentMethod['paymentMethodToken'], $paymentMethod['paymentMethodToken']);
        $this->assertEquals($samplePaymentMethod['customerId'], $paymentMethod['customerId']);

        return $paymentMethod;
    }

    /**
     * Can delete customer payment method
     * @todo can't find a record with payment method not primary
     * @test
     * @return void
     */
    public function deletePrimaryPaymentMethod() {
        $this->expectException(\Exception::class);

        // Arrange
        if (!isset($this->primaryPaymentMethod)) {
            $this->getCustomerPrimaryPaymentMethod();
        }
        $paymentMethod = $this->primaryPaymentMethod;

        Ezypay::deletePaymentMethodByCustomerId($paymentMethod['customerId'], $paymentMethod['paymentMethodToken']);
    }

    /**
     * Getting ezypay payment details via API
     *
     * @test
     * @return void
     */
    public function getPaymentDetailsViaAPI() {
        // Arrange
        // Check payemnt details for customer $this->$ezypayCustomerID
        $this->createAPICredentials(false, false);
        $this->createClientSubscription($this->client);

        $ezypay = EzypayModel::create([
            'ezypay_customer_id' => $this->ezypayCustomerID,
            'identifiable_id' => $this->client->id,
            'identifiable_type' => 'Client',
            'payment_method_token' => $this->ezypayPaymentMethodToken
        ]);

        $this->client->ezypay_id = $ezypay->id;
        $this->client->save();

        // Act
        $response = $this->actingAs($this->user, 'api')->call('get', '/api/v1/account/payment/', [], $this->cookie);

        // Assert
        $response->assertJson([
            'type' => 'CARD',
            'card_type' => 'MASTERCARD'
        ]);
    }
}
