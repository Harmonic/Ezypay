<?php

namespace harmonic\Ezypay\Traits;

use Symfony\Component\Routing\Exception\InvalidParameterException;
use harmonic\Ezypay\Enums\WebHookEventTypes;

trait WebHook {
    /**
     * List webhooks
     *
     * @param integer $limit
     * @param integer $cursor
     * @return array Object Webhooks
     */
    public function getWebhooks(int $limit = null, int $cursor = null) {
        $filters = [
            'limit' => $limit,
            'cursor' => $cursor
        ];

        return $this->request('GET', 'webhooks', $filters);
    }

    /**
     * Create a webhook/url match
     *
     * @param string $url The webhook endpoint to call
     * @param array $eventTypes The EzypayWebHookEventType that causes the webhook to be called
     * @return obj Webhook details
     */
    public function createWebHook(string $url, array $eventTypes) {
        $data = [
            'url' => $url,
            'eventTypes' => $eventTypes
        ];

        $response = $this->request('POST', 'webhooks', $data);

        return \harmonic\Ezypay\Resources\WebHook::make($response)->resolve();
    }

    /**
     * List webhook notification logs
     *
     * @param integer $limit
     * @param integer $cursor
     * @param string $eventId
     * @param string $eventType
     * @param string $status
     * @return void
     */
    public function getWebhookNotificationLogs(string $eventId = null, string $eventType = null, string $status = null, int $limit = null, int $cursor = null) {
        $filters = [
            'limit' => $limit,
            'cursor' => $cursor,
            'eventId' => $eventId,
            'eventType' => $eventType,
            'status' => $status
        ];

        return $this->request('GET', 'webhooks/logs', $filters);
    }

    /**
     * Simulate a webhook event for testing
     *
     * @param string $eventType
     * @return void
     */
    public function simulateWebHook(string $eventType) {
        if (config('app.env') == 'production') {
            throw new \Exception('Cannot run webhook test in production');
        }

        if ($eventType !== null && !WebHookEventTypes::hasKey($eventType)) {
            throw new InvalidParameterException("Event type must be a valid event type from harmonic\Enums\WebHookEventTypes");
        }
        $data['eventType'] = $eventType;

        $response = $this->request('POST', 'webhooks/simulate', $data);

        return $response;
    }

    /**
     * Retrieve webhook details
     *
     * @param string $webhookId
     * @return void
     */
    public function getWebhookDetails(string $webhookId) {
        $response = $this->request('GET', 'webhooks/' . $webhookId);
        return \harmonic\Ezypay\Resources\WebHook::make($response)->resolve();
    }

    /**
     * Update webhook
     *
     * @param string $webhookId
     * @param string $url
     * @param array $eventTypes
     * @param string $bodyWebhookId Please see https://developer.ezypay.com/reference#updatewebhookusingput under body params
     * @param boolean $updateSecurity
     * @return Object Webhook
     */
    public function updateWebhook(string $webhookId, string $url = null, array $eventTypes = [], string $bodyWebhookId = null, bool $updateSecurity = true) {
        $data = [
            'url' => $url,
            'eventTypes' => $eventTypes,
            'webhookId' => $bodyWebhookId,
            'updateSecurity' => $updateSecurity
        ];

        $response = $this->request('PUT', 'webhooks/' . $webhookId, $data);
        return \harmonic\Ezypay\Resources\WebHook::make($response)->resolve();
    }

    /**
     * Delete webhook
     *
     * @param string $webhookId
     * @return void
     */
    public function deleteWebhook(string $webhookId) {
        return $this->request('DELETE', 'webhooks/' . $webhookId);
    }
}
