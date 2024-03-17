<?php

namespace App\Http\Controllers\Api;

use App\Models\Provider;
use Illuminate\Http\Request;
use App\Models\SystemAccount;
use App\Models\ProviderPayment;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ProviderPaymentResource;
use App\Http\Requests\ProviderPaymentFormRequest;
use App\Rules\ValideProviderPayment;

class ProviderPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ProviderPayment::with(['user', 'provider'])->paginate(50);
    }


    public function getProvidersToPay()
    {
        return ProviderPaymentResource::collection(Provider::where('claim', '!=', 0)->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProviderPaymentFormRequest $request)
    {
        // Get provider and system_account
        $provider = Provider::findOrFail($request->provider_id);
        $systemAccount = SystemAccount::findOrFail(1);

        // Validation
        $request->validate([
            'amount' => new ValideProviderPayment($provider->claim, $systemAccount->depts, $systemAccount->funds, $systemAccount->revenues),
        ]);

        // Initialise Provider payent
        $payment = new ProviderPayment;
        $payment->amount = $request->amount;
        $payment->provider_current_claims = $provider->claim;
        $payment->system_current_depts = $systemAccount->depts;

        // Descrease provider claim
        $provider->update(['claim' => ($provider->claim - $request->amount)]);
        // Descrease System_account depts nand fund
        $systemAccount->update(['depts' => ($systemAccount->depts - $request->amount)]);
        $systemAccount->update(['funds' => ($systemAccount->funds - $request->amount)]);

        // Resume Provider payment
        $payment->provider_new_claims = $provider->claim;
        $payment->system_new_depts = $systemAccount->depts;
        $payment->user_id = Auth::id();
        $payment->provider_id = $request->provider_id;
        $payment->status = 'completed';
        $payment->comment = 'test';
        $payment->save();

        return ProviderPayment::all();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProviderPayment  $providerPayment
     * @return \Illuminate\Http\Response
     */
    public function show(ProviderPayment $providerPayment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProviderPayment  $providerPayment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProviderPayment $providerPayment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProviderPayment  $providerPayment
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProviderPayment $providerPayment)
    {
        //
    }
}
