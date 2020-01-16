<?php

namespace harmonic\Ezypay\Tests;

use harmonic\Ezypay\Ezypay;
use Illuminate\Support\Facades\Storage;

class ConnectionTest extends \Orchestra\Testbench\TestCase
{
    /**
     * get token.
     *
     * @test
     * @return void
     */
    public function getFreshToken()
    {
        // Set up an expired token to test with
        try {
            $dotenv = \Dotenv\Dotenv::create(__DIR__, '../.env');
            $dotenv->load();
        } catch (\Dotenv\Exception\InvalidPathException $e) {
            $this->markTestSkipped('Do not test this on travis for now.');
        }

        config([
            'ezypay.token_url' => 'https://identity-sandbox.ezypay.com/token',
            'ezypay.client_id' => getenv('EZY_PAY_API_CLIENT_ID'),
            'ezypay.client_secret' => getenv('EZY_PAY_CLIENT_SECRET'),
            'ezypay.user' => getenv('EZY_PAY_USER'),
            'ezypay.password' => getenv('EZY_PAY_PASSWORD')
        ]);
        $expiredToken = '{"token_type":"Bearer","expires_in":3600,"access_token":"eyJraWQiOiIzd0dCZkhqcG9jMzM5WElXVzhkQmw5SE5aNnpqTXhmQTFlam9OUEpXYk80IiwiYWxnIjoiUlMyNTYifQ.eyJ2ZXIiOjEsImp0aSI6IkFULjZRVVRaM1doVFhQU2VwTGhmYmJ3OGlId002YWYyYl94WVJNU3VvbHViN28ua25LbE1mQjk3UWRibU5FM1o2MkZ1WTRvSXFlcTlPL1FrTW1zdHdUT3RSYz0iLCJpc3MiOiJodHRwczovL2V6eXBheS5va3RhcHJldmlldy5jb20vb2F1dGgyL2F1c2k4aHJ5ajg1a21hSnlJMGg3IiwiYXVkIjoiZXp5cGF5IiwiaWF0IjoxNTY1NTgxNDM0LCJleHAiOjE1NjU1ODUzMzQsImNpZCI6IjBvYWw0aGkxN3VxbjB2NHBwMGg3IiwidWlkIjoiMDB1bDR2bjg4ZERZc0x1REQwaDciLCJzY3AiOlsiaW50ZWdyYXRvciIsIm9mZmxpbmVfYWNjZXNzIiwiYmlsbGluZ19wcm9maWxlIiwiY3JlYXRlX3BheW1lbnRfbWV0aG9kIl0sInN1YiI6ImlkLnRlc3RAaGFybW9uaWMubmV3Lm1lZGlhLmNvbSIsInJvbGUiOlsiRXZlcnlvbmUiLCI3OWVjMmYzNy0zZWJkLTQxMjAtYmRjMi03N2JlNDdmN2ZmYzAiLCJVU0VSIl0sIm1lcmNoYW50X2lkIjoiOGJlZTZmNzAtMGQyMC00MThmLTgwYmItYzRhZGY5OGFiMzRlIiwiaW50ZWdyYXRvcl9pZCI6Ijc5ZWMyZjM3LTNlYmQtNDEyMC1iZGMyLTc3YmU0N2Y3ZmZjMCJ9.dNjDBa8lcCdoMMQSnfulP0UCT9smLg53X7XsFuJzPrbZiga2p-Yfw30FVafVVIGNthDoLTyWwfFVXRTBjxFc69nOn-ZZu3fr4moKTsXqVKtwDAcW_VMeJ-c1ybCzJbaknW0Zy1eNJp9ksg-r3kKcxHGm_xJlkpeqNkgD4xDQZshWkVkOQCaGt2jANwMLcSz1caJI9yUs0mn70RTl-Saj7G2d37riaiCVwOTIoEOyWKT_xXDg2xiISeiYkdic7EKDvTCfmYg4a8YEBmoRMr8Nw0uqPvdcXNm2eafRxIt1rKrWyDAZr77LPMfth-0QtqgwFRVUZZ7fMzM_S5VuPuFdQA","scope":"integrator offline_access billing_profile create_payment_method","refresh_token":"SOB-sTMejH_5ALQGdTt-XFvqE3ZGSwN9w2FPGGpYQT0","expiration":"2019-08-10T04:43:45.531159Z"}';
        Storage::disk('local')->put('ezypayToken.txt', $expiredToken);
        $expiredTokenObj = json_decode($expiredToken);

        // Act
        new Ezypay();
        $newToken = json_decode(Storage::disk('local')->get('ezypayToken.txt'));

        // Assert
        $this->assertNotSame($newToken->access_token, $expiredTokenObj->access_token); // We should have a new token as Ezypay request() should retry
        $oldExpiryDate = new \DateTime($expiredTokenObj->expiration);
        $newExpiryDate = new \DateTime($newToken->expiration);
        $this->assertTrue($newExpiryDate > $oldExpiryDate);
    }
}
