<?php

namespace harmonic\Ezypay\Traits;

trait Event
{
    /**
     * Resend event.
     *
     * @param string $eventId
     * @return object Event
     */
    public function resendEvent(string $eventId)
    {
        return $this->request('POST', 'events/'.$eventId.'/resendwebhook');
    }
}
