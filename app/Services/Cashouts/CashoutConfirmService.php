<?php

namespace App\Services\Cashouts;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Cashout;
use App\Events\CashoutEvent;
use App\Models\CashinActivit;
use App\Models\CashoutActivit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Rules\CheckIfAmountLesThenRecharge;
use App\Rules\CheckIfTransfertLessThenUserProfits;

class CashoutConfirmService
{
    public function cashoutConfirmService($cashout, $user, $envoy, $systemAccount)
    {
        define('USER_CURRENT_PROFIT', $cashout->commission_category === 'commission' ? $user->user_account->profits : $user->user_account->referral_commissions);
        define('USER_NEW_PROFIT', USER_CURRENT_PROFIT - $cashout->amount);

        define('ENVOY_CURRENT_DEBT', $envoy->user_account->depts);
        define('ENVOY_NEW_DEBT', $envoy->user_account->depts - $cashout->amount);

        $laravelTime = config('app.timezone');
        Carbon::setTestNow(Carbon::now($laravelTime));
    

        switch ($cashout) {
            case $cashout->status == 'pending':
                try {
                    DB::beginTransaction();

                    switch (Auth::check()) {
                        case Auth::user()->hasRole('envoy'):
                         
                            if ($cashout->user_role == 'envoy') {
                                $cashout->update([
                                    'confirmed_by_user' => true,
                                    'receiver_confirmation_date' => Carbon::now($laravelTime),
                                    'status' => 'confirmed',
                                    'confirmed_by_envoy' => true,
                                    'envoy_confirmation_date' => Carbon::now($laravelTime),
                                ]);
                            
                                $user->user_account->update([
                                    'depts' => ENVOY_NEW_DEBT,
                                    $cashout->commission_category === 'commission' ? 'profits' : 'referral_commissions' => USER_NEW_PROFIT
                                ]);
                            }else {
                                $cashout->update([
                                    'confirmed_by_envoy' => true,
                                    'envoy_confirmation_date' => Carbon::now($laravelTime)
                                ]);
                                //$envoy->user_account->update(['depts' => ENVOY_CURRENT_DEBT]);
                            }
                            
                            //$cashout->use_envoy_debt ?? $envoy->user_account->update(['depts' => (ENVOY_CURRENT_DEBT + $cashout->amount)]);
                        
                            // History
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
                            $cashout_history->confirmed_by_user = $cashout->confirmed_by_user;
                            $cashout_history->receiver_confirmation_date = $cashout->receiver_confirmation_date;
                            $cashout_history->confirmed_by_envoy = $cashout->confirmed_by_envoy;
                            $cashout_history->envoy_confirmation_date = $cashout->envoy_confirmation_date;
                            $cashout_history->commission_category = $cashout->commission_category;
            
                            $cashout_history->current_user_balance = USER_CURRENT_PROFIT;
                            $cashout_history->new_user_balance = USER_CURRENT_PROFIT;    
                            $cashout_history->current_envoy_debt = ENVOY_CURRENT_DEBT;
                            if (Auth::user()->hasRole('envoy') && $cashout->user_role == 'envoy' || $cashout->user_role == 'system-engineer') {
                                $cashout_history->new_envoy_debt = ENVOY_NEW_DEBT; 
                            } 
                            if (Auth::user()->hasRole('envoy') && $cashout->user_role != 'envoy' || $cashout->user_role != 'system-engineer') {
                                $cashout_history->new_envoy_debt = ENVOY_CURRENT_DEBT; 
                            } 
                            // if (Auth::user()->hasRole('super-admin') || Auth::user()->hasRole('admin') || Auth::user()->hasRole('system-engineer')) {
                            //     $cashout_history->new_envoy_debt = ENVOY_NEW_DEBT; 
                            // }   
                            $cashout_history->current_system_fund = 0.0; 
                            $cashout_history->new_system_fund = 0.0;
                            $cashout_history->step = Auth::user()->getRoleNames()->first() .'-admin_confirmation';
                            $cashout_history->save();
                            // End history
                        break;
                        
                        default:
                            $cashout->update(['confirmed_by_user' => true]);
                            $cashout->update(['receiver_confirmation_date' => Carbon::now($laravelTime)]);

                            if ($cashout->status == 'pending' && $cashout->confirmed_by_user && $cashout->confirmed_by_envoy && $cashout->completed_by_admin) {
                                $cashout->update(['status' => 'confirmed']);
                                // Discrease envoy debt
                                $envoy->user_account->update(['depts' => ENVOY_NEW_DEBT]);
                                // Discrease system account
                                $systemAccount->update(['user_payroll' => $systemAccount->user_payroll - $cashout->amount]);

                                $user->user_account->update([
                                    $cashout->commission_category === 'commission' ? 'profits' : 'referral_commissions' => USER_NEW_PROFIT
                                ]);   
                            }

                             $pendindg_history = CashinActivit::latest()->first();

                              // History
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
                              $cashout_history->confirmed_by_user = $cashout->confirmed_by_user;
                              $cashout_history->receiver_confirmation_date = $cashout->receiver_confirmation_date;
                              $cashout_history->confirmed_by_envoy = $cashout->confirmed_by_envoy;
                              $cashout_history->envoy_confirmation_date = $cashout->envoy_confirmation_date;
                              $cashout_history->commission_category = $cashout->commission_category;
 
                              $cashout_history->current_user_balance = $pendindg_history->current_user_balance;
                              $cashout_history->new_user_balance = $cashout->user_role != 'envoy' ? USER_NEW_PROFIT : $envoy->user_account->referral_commissions - $cashout->amount;    
                              $cashout_history->current_envoy_debt = $pendindg_history->current_envoy_debt;
                              $cashout_history->new_envoy_debt = ENVOY_NEW_DEBT; 
                            //   if (Auth::user()->hasRole('super-admin') || Auth::user()->hasRole('admin') || Auth::user()->hasRole('system-engineer')) {
                            //       $cashout_history->new_envoy_debt = ENVOY_NEW_DEBT; 
                            //   }   
                              $cashout_history->current_system_fund = 0.0; 
                              $cashout_history->new_system_fund = 0.0;
                              $cashout_history->step = Auth::user()->getRoleNames()->first() .'-admin_confirmation';
                              $cashout_history->save();
                              // End history
                        break;
                    }
                     
                    //Create Envent
                     event(new CashoutEvent($cashout, $user));
                     DB::commit();
                     return response()->json(["message" => "Cashout request success"], 200);
                 } catch (\Exception $e) {
                     DB::rollBack();
                     return response()->json(['error' => 'An error occurred during cashout process. Please try again. Error: ' . $e->getMessage()], 500);
                 }
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
    }
}