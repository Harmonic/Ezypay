<?php

namespace harmonic\Ezypay\Enums;

use BenSampo\Enum\Enum;

final class WebHookEventTypes extends Enum {
    const customer_update = 1;
    const customer_create = 2;
    const invoice_created = 3;
    const invoice_past_due = 4;
    const invoice_paid = 5;
    const invoice_unpaid = 6;
    const credit_note_paid = 7;
    const credit_note_failed = 8;
    const credit_note_created = 9;
    const subscription_create = 10;
    const subscription_activate = 11;
    const subscription_cancel = 12;
    const payment_method_changed = 13;
    const payment_method_invalid = 14;
    const payment_method_linked = 15;
    const payment_method_replaced = 16;
    const subscription_payment_reactivate = 17;
    const partner_invoice_created = 18;
    const partner_invoice_paid = 19;
    const partner_invoice_past_due = 20;
}
