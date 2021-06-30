<?php

namespace App\Contracts\Payments;

use Illuminate\Http\Request;

/**
 *
 */
interface Processor
{
    public function prepare(array $data): self;

    public function authourize();

    public function capture();

    public function charge($amount);

}
