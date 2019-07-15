<?php

namespace harmonic\Ezypay\Tests;

use harmonic\Ezypay\Facades\Ezypay;

/**
 * @note run customer test before running this test to make sure there's customer already created
 */
class MerchantTest extends EzypayBaseTest
{
    /**
     * Get merchant details.
     *
     * @test
     * @return void
     */
    public function getMerchantDetails()
    {
        // Arrange

        // Act
        $merchant = Ezypay::getMerchant();

        // Assert
        $this->assertEquals('Harmonic New Media Test', $merchant['name']);
    }
}
