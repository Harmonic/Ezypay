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
        // Act

        $creditNotes = Ezypay::getCreditNotes($this->ezypayCustomerID);

        // Assert
        $this->assertInternalType('array', $creditNotes);
        $this->assertEquals($this->ezypayCustomerID, $creditNotes[0]['customer_id']);

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
        $creditNotes = Ezypay::getCreditNotes(
            $this->ezypayCustomerID,
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
        $creditNote = Ezypay::getCreditNote($creditNotes[0]['id']);

        $this->assertNotNull($creditNote['id']);
        $this->assertEquals($creditNotes[0]['id'], $creditNote['id']);
    }
}
