<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Str;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Contracts\Payments\Processor;
use App\Payments\Factory;
use App\Models\PaymentPipeline;
use App\Models\Order;
use App\Models\User;


class ProcessPayment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    
   

    public function __construct(
        private User $user,
        private PaymentPipeline $pipeline,
        private Order $order,
        private string $number,
      
        )
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $processor = app(Factory::class)->getProcessor('mobile');

        $payment = $processor->prepare([
            'phone' => $this->number,
            'email' => $this->user->email,
            'name' => $this->user->name,
        ])->charge($this->order->amount);

        if ($processor->status != 'success') {
            $this->pipeline->status = -1;
            $this->pipeline->status_string = 'failed';
            $this->pipeline->failure_reason = $payment->getFailureReason();
            return $this->pipeline->save();
        }

        $payment = $this->user->payments()->create([
            'amount' => (float) Str::after($payment->getPaidAmount(), '$'),
            'order_id' => $this->order->id,
            'reference' => $payment->getReference(),
        ]);

        $this->pipeline->payment_id = $payment->id;
        $this->pipeline->status = 1;
        $this->pipeline->status_string = 'success';
        $this->pipeline->save();
    }
}
