<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Order::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->admin) {
            $orders = Order::all()->filter(function ($order) {
                return $order->paid;
            });
        } else {
            $orders = auth()->user()->orders;
        }

        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        \Cart::session($request->user()->id);

        $amount = \Cart::getTotal();

        $order = $request->user()->orders()->create([
            'amount' => $amount
        ]);

        \Cart::getContent()->each(fn ($item) =>
            $order->details()->create([
                'item' => $item->name,
                'quantity' => $item->quantity,
                'unit_price' => $item->price,
            ])
        );

        \Cart::clear();

        return redirect()->route('payments.checkout', $order)->with('success', 'Order was successfuly placed!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return view('orders.show', compact('order'));
    }

    public function find(Order $order)
    {
        return view('orders.index', [
            'orders' => collect([$order])
        ]);
    }

    public function collect(Order $order)
    {
        $order->collected = true;
        $order->save();

        return redirect()->route('orders.index')->with('success', 'This order has been marked as collected!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('orders.index')->with('success', 'You have successfuly canceled your order');
    }
}
