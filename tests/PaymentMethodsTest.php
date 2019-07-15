<?php

namespace harmonic\Ezypay\Tests;

use harmonic\Ezypay\Facades\Ezypay;
use Illuminate\Foundation\Testing\WithFaker;

class PaymentMethodsTest extends EzypayBaseTest
{
    use WithFaker;

    /**
     * Can get a list of customer payment methods.
     *
     * @test
     * @return void
     */
    public function getAListOfPaymentMethods()
    {
        // Arrange
        $customerId = $this->faker->uuid;

        // Act
        $paymentMethods = Ezypay::getPaymentMethods($customerId, false);

        // Assert
        $this->assertInternalType('array', $paymentMethods);
        $this->assertEquals($customerId, $paymentMethods['data'][0]['customerId']);
        $this->assertEquals($paymentMethods['paging']['totalCount'], 1);
    }

    /**
     * Can relate a customer to a payment method.
     *
     * @test
     * @return void
     */
    public function canCreatePaymentMethodForCustomer()
    {
        // Arrange
        $paymentMethodToken = $this->faker->uuid;
        $customer = Ezypay::createCustomer();
        // Act

        $paymentMethod = Ezypay::createPaymentMethod($customer['id'], $paymentMethodToken);

        // Assert
        $this->assertEquals($paymentMethodToken, $paymentMethod['paymentMethodToken']);
        $this->assertEquals($customer['id'], $paymentMethod['customerId']);
        $this->assertEquals($paymentMethod['primary'], true);

        $this->paymentMethod = $paymentMethod;

        return $paymentMethod;
    }

    /**
     * Can get customer primary payment method.
     *
     * @test
     * @return object PaymentMethod
     */
    public function getCustomerPrimaryPaymentMethod()
    {
        // Arrange
        if (! isset($this->paymentMethod)) {
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
     * Can get customer payment method by token.
     *
     * @test
     * @return void
     */
    public function canGetPaymentMethodByToken()
    {
        // Act
        if (! isset($this->paymentMethod)) {
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
     * Can delete customer payment method.
     * @todo can't find a record with payment method not primary
     * @test
     * @return void
     */
    public function deletePrimaryPaymentMethod()
    {
        $this->expectException(\Exception::class);

        // Arrange
        if (! isset($this->primaryPaymentMethod)) {
            $this->getCustomerPrimaryPaymentMethod();
        }
        $paymentMethod = $this->primaryPaymentMethod;

        Ezypay::deletePaymentMethodByCustomerId($paymentMethod['customerId'], $paymentMethod['paymentMethodToken']);
    }
}
