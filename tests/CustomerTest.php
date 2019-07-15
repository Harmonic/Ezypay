<?php

namespace harmonic\Ezypay\Tests;

use harmonic\Ezypay\Facades\Ezypay;
use Illuminate\Foundation\Testing\WithFaker;

class CustomerTest extends EzypayBaseTest
{
    use WithFaker;

    /**
     * Create a test customer.
     *
     * @test
     * @return void
     */
    public function createACustomer()
    {
        // Arrange

        // Act
        $customer = $this->createTestCustomer();

        // Assert
        $this->assertNotNull($customer);

        return $customer;
    }

    /**
     * Can get a list of customers.
     *
     * @test
     * @return void
     */
    public function getAListOfCustomers()
    {
        // Arrange
        // Act
        $customers = Ezypay::getCustomers(true, 'testName');

        // Assert
        $this->assertTrue(array_key_exists('data', $customers));
        $this->assertTrue(array_key_exists('email', $customers['data'][0]));
        $this->assertTrue(array_key_exists('address', $customers['data'][0]));
    }

    /**
     * Can get specific Customer.
     *
     * @test
     * @return void
     */
    public function getCustomerById()
    {
        // Arrange
        // Act
        $customers = Ezypay::getCustomers();

        $customer = Ezypay::getCustomer($customers['data'][0]['id']);

        // Assert
        $this->assertEquals($customers['data'][0]['id'], $customer['id']);

        $this->assertTrue(array_key_exists('id', $customer));
        $this->assertTrue(array_key_exists('email', $customer));
        $this->assertTrue(array_key_exists('address', $customer));
        $this->assertTrue(array_key_exists('address1', $customer['address']));
    }

    /**
     * Update Customer.
     *
     * @test
     * @return void
     */
    public function updateCustomer()
    {
        // Arrange
        $this->faker->addProvider(new \Faker\Provider\en_AU\Address($this->faker));
        $firstName = $this->faker->firstName;
        $email = $this->faker->email;
        // Act

        $customers = Ezypay::getCustomers();
        $customerToUpdate = $customers['data'][0];

        $updatedCustomer = Ezypay::updateCustomer(
            $customerToUpdate['id'],
            $email,
            $firstName,
            $customerToUpdate['lastName'],
            $customerToUpdate['address']['address1']
        );

        // Assert
        $this->assertEquals($customerToUpdate['id'], $updatedCustomer['id']);
        $this->assertEquals($updatedCustomer['email'], $email);
        $this->assertEquals($updatedCustomer['firstName'], $firstName);
    }
}
