<?php

namespace harmonic\Ezypay\Tests;

use harmonic\Ezypay\Tests\EzypayBaseTest;
use harmonic\Ezypay\Facades\Ezypay;
use Illuminate\Support\Carbon;
use harmonic\Models\Ezypay as EzypayModel;
use Illuminate\Foundation\Testing\WithFaker;

class VaultTest extends EzypayBaseTest {
    use WithFaker;

    /**
     * Create a bank payment vault method
     *
     * @test
     * @return void
     */
    public function createABankPaymentVaultMethod() {
        // Arrange
        $accountHolderName = $this->faker->company;
        $accountNumber = $this->faker->randomNumber(9);
        //$bsb = $this->faker->randomNumber(6);
        $bsb = '086488'; // Must be a valid account number

        // Act
        $vaultPayment = Ezypay::createBankPaymentMethod(
            $accountHolderName,
            $accountNumber,
            $bsb
        );

        // Assert
        $this->assertEquals(substr($accountNumber, -4), $vaultPayment['bank']['last4']);
        $this->assertNotNull($vaultPayment['paymentMethodToken']);

        $this->vaultPayment = $vaultPayment;
    }

    /**
     * Create a credit card payment vault method
     *
     * @test
     * @return void
     */
    public function createACreditCardPaymentVaultMethod() {
        // Arrange
        $accountHolderName = $this->faker->firstName . ' ' . $this->faker->lastName;
        $cardNumber = $this->faker->creditCardNumber;
        $expiryMonth = $this->faker->month();
        $expiryYear = $this->faker->dateTimeBetween('now', '10 years')->format('y');

        // Act
        $vaultPayment = Ezypay::createCreditCardPaymentMethod($accountHolderName, $cardNumber, $expiryMonth, $expiryYear);

        // Assert
        $this->assertNotNull($vaultPayment['paymentMethodToken']);
        $this->assertEquals($vaultPayment['type'], 'CARD');
        $this->assertEquals(substr($cardNumber, -4), $vaultPayment['card']['last4']);
    }

    /**
     * Can Retrieve a payment method token
     *
     * @test
     * @return void
     */
    public function canGetPaymentMethod() {
        // Arrange
        if (!isset($this->vaultPayment)) {
            $this->createABankPaymentVaultMethod();
        }

        // Act
        $createdPaymentMethod = $this->vaultPayment;
        $paymentMethod = Ezypay::getVaultPaymentMethodToken($createdPaymentMethod['paymentMethodToken']);

        // Assert
        $this->assertNotNull($paymentMethod);
        $this->assertEquals($createdPaymentMethod['paymentMethodToken'], $paymentMethod['paymentMethodToken']);
    }
}
