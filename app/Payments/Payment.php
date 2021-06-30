<?php

namespace App\Payments;

use App\Contracts\Payments\Payment as PaymentContract;

/**
 *
 */
class Payment implements PaymentContract
{
    public $reference;

    public $transactionId;

    public $amount;

    public $name;

    public $isSuccessful = false;

    public $failureReason;

    public $transactionFees;

    public $amountPaid;

    public $phone;

    public function getReference()
    {
        return $this->reference;
    }

    public function getTransactionId()
    {
        return $this->transactionId;
    }

    public function getPaidAmount()
    {
        return $this->amountPaid;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function getName()
    {
        return $this->name;
    }

    public function isCaptured(): bool
    {
        return false;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function isSuccessful(): bool
    {
        return $this->isSuccessful;
    }

    public function getFailureReason()
    {
        return $this->failureReason;
    }
}
