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
        $customer = Ezypay::createCustomer();
        $subscription = Ezypay::createSubscription($customer['id']);

        $this->assertNotNull($subscription['id']);
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
        $plan = Ezypay::createPlan('Testing Plan', uniqid(), 50.1);
        $customer = Ezypay::createCustomer();
        
        // Act

        $subscription = Ezypay::previewSubscription($customer['id'], $plan['id']);

        // Assert
        //TODO: Needs completing
        $this->assertTrue(array_key_exists('data', $subscription));
        $this->assertTrue(array_key_exists('id', $subscription['data'][0]));
        $this->assertTrue(array_key_exists('customerId', $subscription['data'][0]));
        $this->assertTrue(array_key_exists('planId', $subscription['data'][0]));
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
        $customer = Ezypay::createCustomer();
        $subscription = Ezypay::createSubscription($customer['id']);

        // Act
        $subscription = Ezypay::getSubscription($subscription['id']);

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
        $subscriptions = Ezypay::getSubscriptions();

        // Assert
        $this->assertTrue(array_key_exists('id', $subscriptions['data'][0]));
        $this->assertTrue(array_key_exists('customerId', $subscriptions['data'][0]));
        $this->assertTrue(array_key_exists('planId', $subscriptions['data'][0]));
    }

    /**
     * Can cancel a Subscription
     * @test
     * @return void
     */
    public function canCancelSubscription()
    {
        // Arrange
        $subscriptions = Ezypay::getSubscriptions();
        $subscriptionToCancel = $subscriptions['data'][0];

        // Act
        $subscription = Ezypay::cancelSubscription($subscriptionToCancel['id']);

        // Assert
        $this->assertNotNull($subscription['id']);
        $this->assertEquals('CANCELLED', $subscription['status']);
        $this->assertEquals($subscriptionToCancel['id'], $subscription['id']);
    }

    /**
     * Update subscription's payment method
     * @test
     * @return void
     */
    public function canUpdateSubscription()
    {
        // Arrange
        $subscriptions = Ezypay::getSubscriptions();
        $subscriptionToUpdate = $subscriptions['data'][0];
        $ezypayPaymentMethodToken = $this->faker->uuid;

        // Act
        $subscription = Ezypay::updateSubscription($subscriptionToUpdate['id'], $ezypayPaymentMethodToken);

        // Assert
        $this->assertNotNull($subscription['id']);
        $this->assertEquals($ezypayPaymentMethodToken, $subscription['paymentMethodToken']);
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
        $subscriptions = Ezypay::getSubscriptions();
        $subscriptionToUpdate = $subscriptions['data'][0];

        $subscription = Ezypay::activateSubscription($subscriptionToUpdate['id'], null, null);

        // Assert
        $this->assertNotNull($subscription['id']);
        $this->assertEquals('ACTIVE', $subscription['status']);
    }
}
