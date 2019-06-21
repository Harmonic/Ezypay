<?php

namespace harmonic\Ezypay\Tests;

use harmonic\Ezypay\Tests\EzypayBaseTest;
use harmonic\Ezypay\Facades\Ezypay;

class EventTest extends EzypayBaseTest {
    /**
     * Can get a list of events
     *
     * @test
     * @return void
     */
    public function getAListOfEvents() {
        $isLocalEnv = $this->appEnv == self::APP_ENV_LOCAL;
        if (!$isLocalEnv) {
            // Arrange
            // Act

            $events = Ezypay::getEvents();

            // Assert
            $this->assertNotNull($events);
        } else {
            $this->assertTrue($isLocalEnv);
        }
    }

    /**
     * Can resend event
     *
     * @test
     * @return void
     */
    public function canResendEvent() {
        $isLocalEnv = $this->appEnv == self::APP_ENV_LOCAL;
        if (!$isLocalEnv) {
            // Arrange
            $events = Ezypay::getEvents(null, null, 1);
            var_dump($events);
            // Act
            $events = Ezypay::resendEvent(($events['data'][0])['id']);

            // Assert
            $this->assertNotNull($events);
        } else {
            $this->assertTrue($isLocalEnv);
        }
    }
}
