<?php

namespace harmonic\Ezypay\Tests;

use harmonic\Ezypay\Tests\EzypayBaseTest;
use harmonic\Ezypay\Facades\Ezypay;

class PlanTest extends EzypayBaseTest {
    /**
     * Can create Plan
     *
     * @test
     * @return void
     */
    public function canCreatePlan() {
        // Arrange
        // Act
        $plan = Ezypay::createPlan('Testing Plan', uniqid(), 50.1);

        // Assert
        $this->assertNotNull($plan);
        $this->assertEquals('Testing Plan', $plan['name']);

        $this->plan = $plan;

        return $plan;
    }

    /**
     * Get a list of plans
     *
     * @test
     * @return void
     */
    public function getsAListOfPlans() {
        // Arrange
        // Act
        $plans = Ezypay::getPlans();

        // Assert
        //$this->assertEquals(3, sizeof($plans->data)); // Causing issues as there are aditional plans
        $this->assertNotNull($plans);

        $this->plans = $plans;
    }

    /**
     * Update a plan
     *
     * @test
     * @return void
     */
    public function canUpdateDetailsOfAPlan() {
        // Arrange
        if (!isset($this->plans)) {
            $this->getsAListOfPlans();
        }
        $plans = $this->plans;
        $oldDetails = $plans[0];

        // Act
        $result = Ezypay::updatePlan($oldDetails['id'], 'New Name', $oldDetails['accountingCode'], 11.00);
        $reset = Ezypay::updatePlan($oldDetails['id'], $oldDetails['name'], $oldDetails['accountingCode'], $oldDetails['amount']['value']);

        // Assert
        $this->assertNotEquals($oldDetails['name'], $result['name']);
        $this->assertNotEquals($oldDetails['amount']['value'], 1234);
        $this->assertEquals($oldDetails['amount']['value'], $reset['amount']['value']);
    }

    /**
     * Can get specific Plan
     *
     * @test
     * @return void
     */
    public function getPlanById() {
        // Arrange
        // Act
        if (!isset($this->plans)) {
            $this->getsAListOfPlans();
        }
        $plans = $this->plans;
        $plan = $plans[0];

        $plan = Ezypay::getPlan($plans[0]['id']);

        // Assert
        $this->assertNotNull($plan);
        $this->assertEquals($plans[0]['id'], $plan['id']);
    }
}
