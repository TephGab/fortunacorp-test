<?php

namespace App\Services\Cashouts;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Cashout;
use App\Events\CashoutEvent;
use App\Models\CashoutActivit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Rules\CheckIfAmountLesThenRecharge;
use App\Rules\CheckIfTransfertLessThenUserProfits;

class CashoutStoreService
{
    public function cashoutStore($request, $user, $systemAccount)
    {
        define('USER_CURRENT_PROFIT', $request->commission_category == 'commission' ? $user->user_account->profits : $user->user_account->referral_commissions);
        define('USER_NEW_PROFIT', USER_CURRENT_PROFIT);

        $laravelTime = config('app.timezone');
        Carbon::setTestNow(Carbon::now($laravelTime));

        $request->validate([
            'commission_category' => 'required',
            'balance_amount_cashout' => [
            'required',
                new CheckIfTransfertLessThenUserProfits($request->commission_category == 'commission' ? $user->user_account->profits : $user->user_account->referral_commissions)
            ],
        ]);
        
        switch ($request->cashout_type) {
            case $request->cashout_type == 'user_account':
                try {
                    DB::beginTransaction();
                    $cashout = new Cashout;
                    $cashout->amount = $request->balance_amount_cashout;
                    $cashout->status = $request->cashout_status;
                    $cashout->type = $request->cashout_type;
                    $cashout->currency = $request->cashout_currency;
                    $cashout->user_id = $user->id;
                    $cashout->user_account_id = $user->user_account->id;
                    $cashout->user_role = Auth::user()->getRoleNames()->first();
                    $cashout->commission_category = $request->commission_category;
                    $cashout->use_envoy_debt = $request->use_envoy_debt;
                    $cashout->save();
    
                    $cashout_history = new CashoutActivit;
                    $cashout_history->amount = $cashout->amount;
                    $cashout_history->status = $cashout->status;
                    $cashout_history->type = $cashout->type;
                    $cashout_history->currency = $cashout->currency;
                    $cashout_history->user_id = $cashout->user_id;
                    $cashout_history->user_account_id = $cashout->user_account_id;
                    $cashout_history->cashout_id = $cashout->id;
                    $cashout_history->use_envoy_debt = $cashout->use_envoy_debt;
                    $cashout_history->commission_category = $cashout->commission_category;
    
                    $cashout_history->current_user_balance = USER_CURRENT_PROFIT;
                    $cashout_history->new_user_balance = USER_NEW_PROFIT;    
                    $cashout_history->current_system_fund = 0.0; 
                    $cashout_history->new_system_fund = 0.0;
                    $cashout_history->step = Auth::user()->getRoleNames()->first() .'-request_initilization';
                    $cashout_history->save();
    
                    //Create Envent
                    event(new CashoutEvent($cashout, $user));
                    DB::commit();
                    return response()->json(["message" => "Cashout request success"], 200);
                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json(['error' => 'An error occurred during cashout process. Please try again. Error: ' . $e->getMessage()], 500);
                }
            break;

            case $request->cashout_type == 'user_bank_account':
                //
            break;
        }
    }
}