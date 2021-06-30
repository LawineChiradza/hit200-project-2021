<?php

namespace App\Payments;

use App\Payments\Exceptions\ProcessorNotFoundException;
use App\Contracts\Payments\Processor as ProcessorContract;


/**
 *
 */
class Factory
{
    /**
     * available payment processors
     * @var array
     */
    protected $processors = [
        'mobile' => \App\Payments\Processors\Mobile\Processor::class,
        'cc'     => \App\Payments\Processors\CreditCard\Processor::class,
    ];

    /**
     * gets the payment processor
     * @param string $processor name
     *
     * @return App\Contracts\Payments\Processors\ProcessorContract;
     */
    public function getProcessor(string $processor): ProcessorContract
    {
        if (!collect($this->processors)->has($processor)) {
            throw new ProcessorNotFoundException("Processor {$processor} was not found in the list known processors", 1);
        }
        return app()->make($this->processors[$processor]);
    }
}
