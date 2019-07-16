<?php

namespace harmonic\Ezypay\Tests;

use harmonic\Ezypay\Facades\Ezypay;

class WebHookTest extends EzypayBaseTest
{
    /**
     * Can get a list of WebHook.
     *
     * @test
     * @return void
     */
    public function getAListOfWebHook()
    {
        // Arrange
        //

        // Act
        $webhooks = Ezypay::getWebhooks();

        // Assert
        $this->assertNotNull($webhooks);
        $this->assertTrue(array_key_exists('data', $webhooks));
    }

    /**
     * Can create WebHook.
     *
     * @test
     * @return void
     */
    public function canCreateWebhook()
    {
        // Arrange
        $url = 'http://api.sample'.uniqid().'.test';

        // Act
        $webhook = Ezypay::createWebhook($url, ['customer_create']);

        // Assert
        $this->assertNotNull($webhook);
        $this->assertEquals($url, $webhook['url']);
    }

    /**
     * Can get a list of WebHook logs.
     *
     * @test
     * @return void
     */
    public function getAListOfWebHookLogs()
    {
        // Arrange

        // Act
        $webhooks = Ezypay::getWebhookNotificationLogs();

        // Assert
        $this->assertNotNull($webhooks);
    }

    /**
     * Can Simulate a webhook event.
     *
     * @test
     * @return void
     */
    public function canSimulateWebhook()
    {
        // Arrange

        // Act

        $webhook = Ezypay::simulateWebHook('customer_create');

        // Assert
        $this->assertNotNull($webhook);
        $this->assertEquals('customer_create', $webhook['event']);
    }

    /**
     * Can Retrieve webhook details.
     *
     * @test
     * @return void
     */
    public function canGetWebhookDetails()
    {
        // Arrange
        $webhooks = Ezypay::getWebhooks(1);

        // Act
        $webhook = Ezypay::getWebhookDetails(($webhooks['data'][0])['id']);

        // Assert
        $this->assertNotNull($webhook);
        $this->assertEquals(($webhooks['data'][0])['id'], $webhook['id']);
    }

    /**
     * Can Update webhook.
     *
     * @test
     * @return void
     */
    public function canUpdateWebhook()
    {
        // Arrange
        $webhooks = Ezypay::getWebhooks(1);

        // Act
        $webhook = Ezypay::updateWebhook(($webhooks['data'][0])['id'], ['partner_invoice_past_due']);

        // Assert
        $this->assertNotNull($webhook);
        $this->assertTrue(in_array('partner_invoice_past_due', $webhook['eventTypes']));
    }

    /**
     * Can delete webhook.
     *
     * @test
     * @return void
     */
    public function canDeleteWebhook()
    {
        // Arrange
        $webhooks = Ezypay::getWebhooks(1);

        // Act
        $webhook = Ezypay::deleteWebhook(($webhooks['data'][0])['id']);

        // Assert
        $this->assertTrue($webhook['deleted']);
    }
}
