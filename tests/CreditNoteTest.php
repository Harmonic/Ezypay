<?php

namespace harmonic\Ezypay\Tests;

use harmonic\Ezypay\Tests\EzypayBaseTest;
use harmonic\Ezypay\Facades\Ezypay;

/**
 * @note run customer test before running this test to make sure there's customer already created
 */
class CreditNoteTest extends EzypayBaseTest {
    /**
     * can get list of notes otherwise no notes found
     *
     * @test
     * @return void
     */
    public function getAListOfCreditNotes() {
        // Arrange
        $customerId = $this->faker->uuid;

        // Act
        $creditNotes = Ezypay::getCreditNotes($customerId);

        // Assert
        $this->assertInternalType('array', $creditNotes);
        $this->assertEquals($customerId, $creditNotes['data'][0]['customer_id']);

        return $creditNotes;
    }

    /**
     * can get record by id
     *
     * @test
     * @return void
     */
    public function canRetriveANoteById() {
        // Arrange
        $customerId = $this->faker->uuid;
        $creditNotes = Ezypay::getCreditNotes(
            $customerId,
            null,
            null,
            false,
            null,
            null,
            null,
            null,
            1
        );

        // Act
        $creditNoteResult = $creditNotes['data'][0];
        $creditNote = Ezypay::getCreditNote($creditNoteResult['id']);

        $this->assertNotNull($creditNote['id']);
        $this->assertEquals($creditNoteResult['id'], $creditNote['id']);
    }
}
