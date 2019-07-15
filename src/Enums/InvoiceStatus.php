<?php

namespace harmonic\Ezypay\Enums;

use BenSampo\Enum\Enum;

final class InvoiceStatus extends Enum
{
    const paid = 0;
    const processing = 1;
    const past_due = 2;
    const refunded = 3;
    const written_off = 4;
}
