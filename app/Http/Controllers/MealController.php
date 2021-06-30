<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\Order;
use Illuminate\Http\Request;

class MealController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Meal::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $meals = Meal::all();

        return view('meals.index', compact('meals'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('meals.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = collect($request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'thumb' => 'required|image'
        ]));

        $meal = Meal::create($data->except('thumb')->all());

        $meal->attachMedia($data['thumb']);

        return redirect()->route('meals.index')->with('success', 'You successfuly added a meal');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Meal  $meal
     * @return \Illuminate\Http\Response
     */
    public function show(Meal $meal)
    {
        return view('meals.show', compact('meal'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Meal  $meal
     * @return \Illuminate\Http\Response
     */
    public function edit(Meal $meal)
    {
        return view('meals.edit', compact('meal'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Meal  $meal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Meal $meal)
    {
        $data = collect($request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'thumb' => 'image'
        ]));

        $meal->update($data->except('thumb')->all());

        if ($data->has('thumb')) {
            $meal->updateMedia($data['thumb']);
        }

        return redirect()->route('meals.index')->with('success', 'Meal was successfuly updated');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Meal  $meal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Meal $meal)
    {
        $meal->detachMedia();
        $meal->delete();
        return redirect()->route('meals.index')->with('success', 'Meal was successfuly deleted');
    }

    public function advisor(Request $request)
    {
        $this->authorize('advise', Meal::class);

        $month = $request->query('month', \Carbon\Carbon::now()->englishMonth);
        $year = $request->query('year', \Carbon\Carbon::now()->year);

        $dataset = [];
        $orders = Order::all()->filter(function ($order) use ($month, $year) {
            return $order->created_at
                        ->setTime(0,0,0)
                        ->setDay(1)->equalTo(
                    (new \Carbon\Carbon($month))
                        ->setTime(0,0,0)
                        ->setYear($year)
                        ->setDay(1)
                );
        });

        $ordered = $orders->pluck('details')->flatten()->groupBy('item');

        foreach($ordered as $item => $order){
            $dataset['pie']['lables'][] = $item;
            $dataset['pie']['data'][] = $order->sum('quantity');
            $red = random_int(0, 255);
            $green = random_int(0, 255);
            $blue = random_int(0, 255);

            $dataset['pie']['colors'][] = "rgba({$red}, {$green}, {$blue})";
        }

        $line = [];

        foreach ($orders as $order) {
            if (isset($line[$order->created_at->shortEnglishDayOfWeek])) {
                $line[$order->created_at->shortEnglishDayOfWeek] = (int) $line[$order->created_at->shortEnglishDayOfWeek] + (int) $order->details->sum('quantity');
                continue;
            }
            $line[$order->created_at->shortEnglishDayOfWeek] = (int) $order->details->sum('quantity');
        }

        $line = collect($line);
        $dataset['line']['lables'] = $line->keys();
        $dataset['line']['data'] = $line->values();
        $dataset = json_encode($dataset);

        return view('meals.advisor', compact('dataset', 'ordered', 'month', 'year'));
    }
}
