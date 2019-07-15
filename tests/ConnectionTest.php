<?php

namespace harmonic\Ezypay\Tests;

use harmonic\Ezypay\Facades\Ezypay;
use harmonic\Ezypay\Tests\EzypayBaseTest;
use Illuminate\Support\Facades\Storage;

class ConnectionTest extends EzypayBaseTest
{
    /**
     * get token
     *
     * @test
     * @return void
     */
    public function getToken() {
        // Act
        $token = Ezypay::getToken();

        // Assert
        $this->assertIsString($token);
    }
}
