<?php

namespace harmonic\Ezypay\Traits;

trait Event {
    /**
     * Resend event
     *
     * @param string $eventId
     * @return Object Event
     */
    public function resendEvent(string $eventId) {
        return $this->request('POST', 'events/' . $eventId . '/resendwebhook');
    }
}
