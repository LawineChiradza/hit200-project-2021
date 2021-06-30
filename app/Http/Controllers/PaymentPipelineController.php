<?php

namespace App\Http\Controllers;

use App\Models\PaymentPipeline;
use App\Models\Order;
use Illuminate\Http\Request;

class PaymentPipelineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PaymentPipeline  $paymentPipeline
     * @return \Illuminate\Http\Response
     */
    public function show(PaymentPipeline $paymentPipeline, Order $order)
    {
        if ($paymentPipeline->status == 1) {
            $order->paid = true;
            $order->save();
            return redirect()->route('orders.show', $order)->with('success', 'You have successfuly paid for this order');
        }

        return view('payments.processing', compact('paymentPipeline', 'order'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PaymentPipeline  $paymentPipeline
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PaymentPipeline $paymentPipeline)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PaymentPipeline  $paymentPipeline
     * @return \Illuminate\Http\Response
     */
    public function destroy(PaymentPipeline $paymentPipeline)
    {
        //
    }
}
