<?php

namespace App\Payments\Processors\CreditCard;

use App\Contracts\Payments\Processor as ProcessorContract;
use App\Contracts\Payments\Payment as PaymentContract;
use App\Payments\Payment;

/**
 *
 */
class Processor implements ProcessorContract
{
    public function prepare(array $data): ProcessorContract
    {
        return $this;
    }

    public function authourize()
    {

    }

    public function capture()
    {

    }

    public function charge($amount): PaymentContract
    {
        return app()->make(Payment::class);
    }
}
