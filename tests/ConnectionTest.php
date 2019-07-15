<?php

namespace harmonic\Ezypay\Tests;

use harmonic\Ezypay\Facades\Ezypay;

class ConnectionTest extends EzypayBaseTest
{
    /**
     * get token.
     *
     * @test
     * @return void
     */
    public function getToken()
    {
        // Act
        $token = Ezypay::getToken();

        // Assert
        $this->assertIsString($token);
    }
}
