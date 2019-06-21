<?php

namespace harmonic\Ezypay\Tests;

use harmonic\Ezypay\Tests\EzypayBaseTest;
use harmonic\Ezypay\Facades\Ezypay;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Testing\WithFaker;

class SubscriptionTest extends EzypayBaseTest
{
    use WithFaker;

    /**
     * Can create new subscription
     *
     * @test
     * @return void
     */
    public function canCreateNewSubscription()
    {
        // Assert
        $customer = $this->createTestCustomer();

        $subscription = &$this->getSharedSubscription();
        $subscription = $this->createSubscription($customer);

        $this->assertNotNull($this->subscription['id']);
    }

    /**
     * Can preview Subscription
     *
     * @test
     * @return void
     */
    public function canPreviewSubscription()
    {
        // Arrange
        $startDate = Carbon::now()->addDays(14);
        $plan = $this->addPlanToDB();

        $epCustomer = &$this->getSharedCustomer();
        
        // Act

        $subscription = Ezypay::previewSubscription($epCustomer['id'], $this->ezypayBronzePlanID, $this->ezypayPaymentMethodToken, $startDate);

        // Assert
        //TODO: Needs completing
    }

    /**
     * Can get specific subscription
     *
     * @test
     * @return void
     */
    public function canGetASubscription()
    {
        // Arrange
        // Act
        $newSubscription = &$this->getSharedSubscription();

        $subscription = Ezypay::getSubscription($newSubscription['id']);

        // Assert
        $this->assertNotNull($subscription['id']);
    }

    /**
     * Can get all subscription
     *
     * @test
     * @return void
     */
    public function canGetSubscriptions()
    {
        // Arrange
        // Act
        $newSubscription = &$this->getSharedSubscription();

        $subscription = Ezypay::getSubscriptions($newSubscription['customerId']);

        // Assert
        $this->assertTrue(array_key_exists('id', $subscription[0]));
        $this->assertTrue(array_key_exists('customerId', $subscription[0]));
        $this->assertTrue(array_key_exists('planId', $subscription[0]));
    }

    /**
     * Can cancel a Subscription
     * @test
     * @return void
     */
    public function canCancelSubscription()
    {
        // Arrange
        // Act
        $newSubscription = &$this->getSharedSubscription();

        $subscription = Ezypay::cancelSubscription($newSubscription['id']);

        // Assert
        $this->assertNotNull($subscription['id']);
        $this->assertEquals('CANCELLED', $subscription['status']);
        $this->assertEquals($newSubscription['id'], $subscription['id']);
    }

    /**
     * Update subscription's payment method
     * @test
     * @return void
     */
    public function canUpdateSubscription()
    {
        // Arrange
        // Act
        $newSubscription = &$this->getSharedSubscription();
        ;

        $subscription = Ezypay::updateSubscription($newSubscription['id'], $this->ezypayPaymentMethodToken);

        // Assert
        $this->assertNotNull($subscription['id']);
        $this->assertEquals($this->ezypayPaymentMethodToken, $subscription['paymentMethodToken']);
    }

    /**
     * Can Activate a subscription
     *
     * @test
     * @return void
     */
    public function canActivateASubscription()
    {
        // Arrange
        // Act
        $customer = &$this->getSharedCustomer();
        $newSubscription = $this->createSubscription($customer, true);

        $subscription = Ezypay::activateSubscription($newSubscription['id'], null, null);

        // Assert
        $this->assertNotNull($subscription['id']);
        $this->assertEquals('active', $subscription['status']);
    }

    /**
     * Test modifying payment details on a subscription
     *
     * @test
     * @return void
     */
    public function canModifySubscriptionPaymentMethod()
    {
        // Arrange
        $this->createAPICredentials(false, false);
        $this->createClientSubscription($this->client);

        $oneYearOn = date('y', strtotime(date('Y-m-d', time()) . ' + 365 day'));
        $vaultPaymentToken = Ezypay::createCreditCardPaymentMethod('John Tester', $this->faker->creditCardNumber, 12, $oneYearOn);

        $cardData = [
            'type' => 'CARD',
            'details' => [
            ],
            'country' => 'AU',
            'name' => 'Account Holder',
            'number' => '5555555555554444',
            'expiry' => '12/29',
            'paymentToken' => $vaultPaymentToken['paymentMethodToken'],
        ];

        $bankData = [
            'type' => 'BANK',
            'details' => [
            ],
            'country' => 'AU',
            'bankAccountName' => 'Account Name',
            'bsb' => '086488',
            'accountNumber' => '12345678',
            'paymentToken' => $vaultPaymentToken['paymentMethodToken'],
        ];

        // Act
        $responseCard = $this->actingAs($this->user, 'api')->call('put', '/api/v1/account/payment', $cardData, $this->cookie);

        // Assert
        $responseCard->assertStatus(200);
        $this->client->refresh();
        $responseBank = $this->actingAs($this->user, 'api')->call('put', '/api/v1/account/payment', $bankData, $this->cookie);
        $responseBank->assertStatus(200);
    }
}
