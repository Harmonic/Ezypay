<?php

namespace harmonic\Ezypay\Tests;

use Illuminate\Support\Carbon;
use harmonic\Ezypay\Facades\Ezypay;
use Illuminate\Foundation\Testing\WithFaker;

abstract class EzypayBaseTest extends \Orchestra\Testbench\TestCase
{
    use WithFaker;

    protected function setUp() : void
    {
        parent::setUp();
        Ezypay::fake();
    }

    /**
     * Create a test customer.
     *
     * @return Customer
     */
    protected function createTestCustomer()
    {
        $this->faker->addProvider(new \Faker\Provider\en_AU\Address($this->faker));
        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastName;
        $email = $this->faker->email;
        $address1 = $this->faker->streetAddress;
        $address2 = '';
        $city = $this->faker->city;
        $state = strtolower($this->faker->stateAbbr);
        $postCode = $this->faker->postcode;
        $country = 'au';
        $companyName = $this->faker->company;
        $customer = Ezypay::createCustomer($firstName, $lastName, $email, $address1, $address2, $postCode, $city, $state, $country, $companyName);

        $this->customer = $customer;

        return $customer;
    }

    protected function &createSubscription(array $customer, bool $isPending = false, bool $returnClient = false)
    {
        $startDate = $isPending ? Carbon::now()->addDays(14) : null;

        $paymentMethod = &$this->getSharedPaymentMethod();
        if ($paymentMethod == null) {
            $paymentMethod = Ezypay::createPaymentMethod($customer['id'], $this->ezypayPaymentMethodToken);
        }

        //TODO: Complete this test

        return $this->subscription;
    }

    protected function &getSharedSubscription()
    {
        static $sharedSubscription = null;

        return $sharedSubscription;
    }

    protected function &getSharedCustomer()
    {
        static $customer = null;

        return $customer;
    }

    protected function &getSharedPaymentMethod()
    {
        static $paymentMethod = null;

        return $paymentMethod;
    }
}
