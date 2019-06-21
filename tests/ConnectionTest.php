<?php

namespace harmonic\Ezypay\Tests;

use harmonic\Ezypay\Facades\Ezypay;
use harmonic\Ezypay\Tests\EzypayBaseTest;
use Illuminate\Support\Facades\Storage;

class ConnectionTest extends EzypayBaseTest
{
    /**
     * Test connection to Ezypay and that we re-use the token
     *
     * @test
     * @return void
     */
    public function ezyPayConnect()
    {
        $tokenFile = 'ezypayToken.txt';

        $ezypay = Ezypay::instance();
        $this->assertTrue(Storage::disk('ezypayTest')->exists($tokenFile));
        $tokenDetails = Storage::disk('ezypayTest')->get($tokenFile);
        $this->assertJson($tokenDetails);
        $tokenObj = json_decode($tokenDetails, true);
        $oldToken = $tokenObj['access_token'];
        $ezypay = Ezypay::instance();
        $tokenDetails = Storage::disk('ezypayTest')->get($tokenFile);
        $tokenObj = json_decode($tokenDetails, true);
        $this->assertEquals($oldToken, $tokenObj['access_token']);
    }
}
