<?php

namespace harmonic\Ezypay\Traits;

trait Event {
    /**
     * Get a list of Events from Ezypay server
     *
     * @param string $eventId
     * @param string $eventType
     * @param integer $limit
     * @param integer $cursor
     * @return array Object Events
     */
    public function getEvents(string $eventId = null, string $eventType = null, int $limit = null, int $cursor = null) {
        $filters = [
            'limit' => $limit,
            'cursor' => $cursor,
            'eventId' => $eventId,
            'eventType' => $eventType
        ];

        return $this->request('GET', 'events', $filters);
    }

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
