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
        // Arrange
        // Act
        $events = Ezypay::getEvents();

        // Assert
        $this->assertNotNull($events);
    }

    /**
     * Can resend event
     *
     * @test
     * @return void
     */
    public function canResendEvent() {
        // Arrange
        $events = Ezypay::getEvents(null, null, 1);

        // Act
        $events = Ezypay::resendEvent(($events['data'][0])['id']);

        // Assert
        $this->assertNotNull($events);
    }
}
