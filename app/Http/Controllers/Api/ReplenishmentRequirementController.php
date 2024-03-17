<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ReplenishmentRequirement;
use Illuminate\Http\Request;

class ReplenishmentRequirementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ReplenishmentRequirement::with(['user', 'transaction'])->where('status', 'pending')->orderBy('created_at', 'DESC')->paginate(50);
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
     * @param  \App\Models\ReplenishmentRequirement  $replenishmentRequirement
     * @return \Illuminate\Http\Response
     */
    public function show(ReplenishmentRequirement $replenishmentRequirement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ReplenishmentRequirement  $replenishmentRequirement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ReplenishmentRequirement $replenishmentRequirement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ReplenishmentRequirement  $replenishmentRequirement
     * @return \Illuminate\Http\Response
     */
    public function destroy(ReplenishmentRequirement $replenishmentRequirement)
    {
        //
    }
}
