<?php

namespace App\Http\Controllers\Api;

use App\Models\Rate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class RateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dailyRateSale = Rate::pluck('daily_rate_sale')->first();
        $dailyRatePurchase = Rate::pluck('daily_rate_purchase')->first();

        return ["dailyRateSale" => $dailyRateSale, "dailyRatePurchase" => $dailyRatePurchase];
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
     * @param  \App\Models\Rate  $rate
     * @return \Illuminate\Http\Response
     */
    public function show(Rate $rate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Rate  $rate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rate $rate)
    {
        // $rate = Rate::findOrFail($rate->id);
        $rate->update(['daily_rate_sale' => $request->daily_rate_sale]);
    }

    public function dailyRateUpdate(Request $request)
    {
        DB::table('rates')->update(['daily_rate_sale' => $request->dailyrateSale, 'daily_rate_purchase' => $request->dailyratePurchase]);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Rate  $rate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rate $rate)
    {
        //
    }
}
