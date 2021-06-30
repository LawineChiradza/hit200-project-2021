<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meal;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('cart.index');
    }

    public function increase(Request $request)
    {
        \Cart::session($request->user()->id)->update($request->item, [
            'quantity' => 1
        ]);

        return redirect()->route('cart.index')->with('success', 'Item quantity was successfuly updated');
    }

    public function decrease(Request $request)
    {
        \Cart::session($request->user()->id)->update($request->item, [
            'quantity' => -1
        ]);

        return redirect()->route('cart.index')->with('success', 'Item quantity was successfuly updated');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'meal_id' => 'required|int',
            'quantity' => 'required|numeric',
        ]);

        $meal = Meal::find($data['meal_id']);

        \Cart::session($request->user()->id)->add([
            'id' => random_int(100000, 999999),
            'name' => $meal->name,
            'price' => $meal->price,
            'quantity' => $data['quantity'],
        ]);

        return redirect()->back()->with('success', 'Item was successfuly added to basket');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        \Cart::session(auth()->user()->id)->remove($id);

        return redirect()->back()->with('success', 'Item was successfuly removed from basket');
    }
}
