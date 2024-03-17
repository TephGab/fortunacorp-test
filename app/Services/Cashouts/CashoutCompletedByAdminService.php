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

class CashoutCompletedByAdminService
{
    public function cashoutCompletedByAdmin($request, $cashout, $envoy, $user)
    {
        define('USER_CURRENT_PROFIT', $user->user_account->profits);
        define('USER_NEW_PROFIT', USER_CURRENT_PROFIT);

        define('ENVOY_CURRENT_DEBT', $envoy->user_account->depts);
       // define('ENVOY_NEW_DEBT', $envoy->user_account->depts);
      
        $laravelTime = config('app.timezone');
        Carbon::setTestNow(Carbon::now($laravelTime));

       if ($cashout->user_role != 'envoy') {
        $request->validate([
            'envoy_id' => 'required'
        ]);
       }
        
        switch ($cashout->type) {
            case $cashout->type == 'user_account':
                try {
                    DB::beginTransaction();
                    switch ($cashout) {
                        case $cashout->status == 'pending':

                            switch ($request->use_envoy_debt) {
                                case $request->use_envoy_debt == false:
                                    $envoy->user_account->update(['depts' => ENVOY_CURRENT_DEBT]);
                                break;
                                
                                default:
                                    if (ENVOY_CURRENT_DEBT < $cashout->amount) {
                                        return response()->json([
                                            'message' => 'The given data was invalid.',
                                            'errors' => [
                                                'insufficient_debts' => [
                                                    'Insufficient envoy debt!'
                                                ]
                                            ]
                                        ], 422);
                                    }
                                break;
                            }

                                $cashout->update(['admin_id' => Auth::id()]);
                                $cashout->update(['completed_by_admin' => 1]);
                                $cashout->update(['admin_completion_date' => Carbon::now($laravelTime)]);
                                $cashout->update(['user_in_person' => $request->user_in_person]);
                                $cashout->update(['use_envoy_debt' => $request->use_envoy_debt]);
                                // $cashout->update(['status' => 'completed']);
                                $cashout->update(['envoy_id' => $envoy->id]);
                            break;
                        
                        default:
                            return response()->json([
                                'message' => 'Cashout in progress.',
                                'errors' => [
                                    'cashout_in_progress' => [
                                        'Cashout in progress!'
                                    ]
                                ]
                            ], 422);
                        break;
                    }
                  
    
                    $cashout_history = new CashoutActivit;
                    $cashout_history->amount = $cashout->amount;
                    $cashout_history->status = $cashout->status;
                    $cashout_history->type = $cashout->type;
                    $cashout_history->currency = $cashout->currency;
                    $cashout_history->user_id = $cashout->user_id;
                    $cashout_history->user_account_id = $cashout->user_account_id;
                    $cashout_history->cashout_id = $cashout->id;


                    $cashout_history->admin_id = $cashout->admin_id;
                    $cashout_history->envoy_id = $cashout->envoy_id;
                    $cashout_history->completed_by_admin = $cashout->completed_by_admin;
                    $cashout_history->admin_completion_date = $cashout->admin_completion_date;
                    $cashout_history->use_envoy_debt = $cashout->use_envoy_debt;
                    $cashout_history->user_in_person = $cashout->user_in_person;
                    $cashout_history->status = $cashout->status;
                    $cashout_history->commission_category = $cashout->commission_category;
    
                    $cashout_history->current_user_balance = USER_CURRENT_PROFIT;
                    $cashout_history->new_user_balance = USER_NEW_PROFIT;    
                    $cashout_history->current_envoy_debt = ENVOY_CURRENT_DEBT;
                    $cashout_history->new_envoy_debt = ENVOY_CURRENT_DEBT;    
                    $cashout_history->current_system_fund = 0.0; 
                    $cashout_history->new_system_fund = 0.0;
                    $cashout_history->step = Auth::user()->getRoleNames()->first() .'-admin_confirmation';
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

            case $cashout->type == 'user_bank_account':
                //
            break;
        }
    }
}