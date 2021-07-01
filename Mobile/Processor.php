<?php

namespace App\Payments\Processors\Mobile;

use App\Contracts\Payments\Processor as ProcessorContract;
use App\Contracts\Payments\Payment as PaymentContract;
use App\Exceptions\PhoneNumberNotKnownException;
use App\Payments\Processors\Mobile\Browser;
use App\Payments\Payment;
use Illuminate\Support\Str;

/**
 *
 */
class Processor implements ProcessorContract
{
    /**
     * The phone number use to make Payment
     * @var string
     */
    private $phoneNumber;

    /**
     * The operator of the mobile
     */
    private $operator;

    /**
     * List of known supported operators
     */
    private $operators = [
        'ecocash', 'onemoney', 'telecash'
    ];

    /**
     * Known mobile operator prefixes
     */
    private $prefixes = [
        '071' => 'onemoney',
        '078' => 'ecocash',
        '077' => 'ecocash',
        '073' => 'telecash'
    ];

    private $merchantEmail;

    private $visitUrl;

    private $clientEmail;

    private $clientName;

    private $amount;

    public $browser;

    public $status;

    public $details;

    /**
     * The url to visit to make the Payment
     */
    protected $paymentBaseUrl = 'https://www.paynow.co.zw/payment/link/';

    public function __construct(Browser $browser)
    {
        $this->browser = $browser;
    }

    /**
     * Prepare the processor with required data
     * @param array $data
     * @throws \App\Payments\Exceptions\PhoneNumberNotKnownException
     * @return this;
     */
    public function prepare(array $data): ProcessorContract
    {
        collect($this->prefixes)->each(function($operator, $prefix) use ($data){
            if (!Str::startsWith($data['phone'], $prefix)) {
                return;
            }
            $this->phoneNumber = $data['phone'];
            $this->operator = $this->prefixes[$prefix];
        });

        if (!isset($this->phoneNumber)) {
            throw new PhoneNumberNotKnownException("The phone number {$data['phone']} is not a Zimbabwean phone", 1);
        }

        $this->merchantEmail = 'chiradzalawine15@gmail.com';

        $this->clientEmail = $data['email'];

        $this->clientName = $data['name'];

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
        $this->amount = $amount;
        $this->configureUrl();

        $this->browser->makePayment($this);

        $payment = app()->make(Payment::class);
        if ($this->status == 'failed') {
            $payment->failureReason = 'Insuficient balance or network problems';
            return $payment;
        }

        $payment->name = $this->details['customer_name'];
        $payment->phone = $this->details['customer_phone'];
        $payment->amount = $this->details['transaction_amount'];
        $payment->reference = $this->details['transaction_id'];
        $payment->transactionId = $this->details['transaction_id'];
        $payment->amountPaid = $this->details['amount_paid'];
        $payment->transactionFees = $this->details['processing_fees'];
        $payment->isSuccessful = true;

        return $payment;
    }

    private function configureUrl()
    {
        $url = $this->paymentBaseUrl;
        $url .= 'name@example.com';
        $url .= '?q=';
        $l = 'search=' . urlencode($this->merchantEmail);
        $l .= '&amount=' . urlencode($this->amount);
        $l .= '&reference=' . urlencode('order');
        $url .= urlencode(base64_encode($l));
        $this->visitUrl = $url;
    }

    public function getVisitUrl()
    {
        return $this->visitUrl;
    }

    public function getPhone()
    {
        return $this->phoneNumber;
    }

    public function getClientName()
    {
        return $this->clientName;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function getClientEmail()
    {
        return $this->clientEmail;
    }

    public function getSelector()
    {
        return '.' . $this->operator . '-row';
    }
}
