<?php
namespace harmonic\Ezypay\Tests;
use harmonic\Ezypay\Tests\EzypayBaseTest;
use harmonic\Ezypay\Facades\Ezypay;

class EventTest extends EzypayBaseTest {
    /**
     * Can resend event
     *
     * @test
     * @return void
     */
    public function canResendEvent() {
        $isLocalEnv = $this->appEnv == self::APP_ENV_LOCAL;
        if (!$isLocalEnv) {
            // Act
            $events = Ezypay::resendEvent($this->faker->uuid);
            // Assert
            $this->assertEmpty($events);
            // Stop here and mark this test as incomplete.
            $this->markTestIncomplete(
                'This test has not been implemented yet.'
            );
        } else {
            $this->assertTrue($isLocalEnv);
        }
    }
}