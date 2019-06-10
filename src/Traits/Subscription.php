<?php

namespace harmonic\Ezypay\Traits;

use Illuminate\Support\Carbon;

trait Subscription {
    public function createSubscription(string $customerId, string $planId, string $paymentMethodToken = null, \Carbon\Carbon $startDate = null, bool $markAsPending = false, bool $customNotification = false) {
        if ($startDate == null) {
            $startDate = Carbon::now()->addDays(config('ezypay.trial_days'));
        }

        $data = [
            'customerId' => $customerId,
            'planId' => $planId,
            'paymentMethodToken' => $paymentMethodToken,
            'startDate' => $startDate->toDateString(),
            'markAsPending' => $markAsPending,
            'customerEmailNotification' => $customNotification
        ];

        $response = $this->request('POST', 'billing/subscriptions', $data);
        return \harmonic\Ezypay\Resources\Subscription::make($response)->resolve();
    }

    public function previewSubscription(string $customerId, string $planId, string $paymentMethodToken = null, \Carbon\Carbon $startDate = null, bool $markAsPending = false) {
        if ($startDate == null) {
            $startDate = Carbon::now()->toDateString();
        }

        $data = [
            'customerId' => $customerId,
            'planId' => $planId,
            'paymentMethodToken' => $paymentMethodToken,
            'startDate' => $startDate != null ? $startDate->toDateString() : null,
            'markAsPending' => $markAsPending
        ];

        $response = $this->request('POST', 'billing/subscriptions/preview', $data);

        return \harmonic\Ezypay\Resources\Subscription::make($response)->resolve();
    }

    /**
     * Retrieve a subscription
     *
     * @param string $subscriptionId
     * @return Object Subscription
     */
    public function getSubscription(string $subscriptionId) {
        $response = $this->request('GET', 'billing/subscriptions/' . $subscriptionId);
        return \harmonic\Ezypay\Resources\Subscription::make($response)->resolve();
    }

    /**
     * Activate subscription
     *
     * @param string $subscriptionId The ID of the subcsription to activate
     * @param string $startDate
     * @param string $paymentMethodToken
     * @return Object Subscription
     */
    public function activateSubscription(string $subscriptionId, string $startDate = null, string $paymentMethodToken = null) {
        $data = [
            'startDate' => $startDate
        ];

        if ($paymentMethodToken != null) {
            $data['paymentMethodToken'] = $paymentMethodToken;
        }

        $response = $this->request(
            'PUT',
            'billing/subscriptions/' . $subscriptionId . '/activate',
            $data
        );

        return \harmonic\Ezypay\Resources\Subscription::make($response)->resolve();
    }

    /**
     * Cancel subscription
     *
     * @param string $subscriptionId The ID of the subcsription to cancel
     * @return Object Subscription
     */
    public function cancelSubscription(string $subscriptionId) {
        $response = $this->request('PUT', 'billing/subscriptions/' . $subscriptionId . '/cancel');
        return \harmonic\Ezypay\Resources\Subscription::make($response)->resolve();
    }

    /**
     * Update subscription's payment method
     *
     * @param string $subscriptionId The ID of the subcsription to update
     * @param string $paymentMethodToken The new payment method token to use for this subscription
     * @return void
     */
    public function updateSubscription(string $subscriptionId, string $paymentMethodToken) {
        $response = $this->request('PUT', 'billing/subscriptions/' . $subscriptionId . '/paymentmethod/' . $paymentMethodToken);
        return \harmonic\Ezypay\Resources\Subscription::make($response)->resolve();
    }

    /**
     * Get all subscriptions from Ezypay
     *
     * @param string $customerId
     * @param boolean $fetchAll
     * @param integer $limit
     * @param integer $cursor
     * @return void
     */
    public function getSubscriptions(string $customerId, bool $fetchAll = false, int $limit = null, int $cursor = null) {
        $data = [
            'customerId' => $customerId,
            'limit' => $limit,
            'cursor' => $cursor
        ];

        return $this->paginate('billing/subscriptions', $data, $fetchAll);
    }
}
