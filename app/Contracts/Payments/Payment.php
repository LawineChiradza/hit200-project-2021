<?php

namespace App\Contracts\Payments;

/**
 *
 */
interface Payment
{
    public function getReference();

    public function getPaidAmount();

    public function getName();

    public function isCaptured(): bool;

    public function getAddress();

    public function isSuccessful(): bool;

    public function getFailureReason();

}
