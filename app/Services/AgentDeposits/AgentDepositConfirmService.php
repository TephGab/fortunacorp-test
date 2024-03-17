<?php

namespace App\Services\AgentDeposits;

use Carbon\Carbon;
use App\Models\AgentDeposit;
use App\Models\SystemAccount;
use App\Models\AgentInvestment;
use App\Models\AgentDebtDeposit;
use App\Events\AgentDepositEvent;
use App\Models\SystemBankAccount;
use Illuminate\Support\Facades\DB;
use App\Models\AgentDepositActivit;
use Illuminate\Support\Facades\Auth;

class AgentDepositConfirmService
{
    public function agentDepositConfirm($agentDeposit, $agent, $envoy)
    {
        define('AGENT_CURRENT_PROFIT', $agent->user_account->profits);
        define('AGENT_NEW_PROFIT', AGENT_CURRENT_PROFIT);

        define('AGENT_CURRENT_INVESTMENT', $agent->user_account->investments);
        define('AGENT_NEW_INVESTMENT', Auth::user()->hasRole('envoy') ? AGENT_CURRENT_INVESTMENT : (AGENT_CURRENT_INVESTMENT + $agentDeposit->amount));

        define('AGENT_CURRENT_DEBT', $agent->user_account->depts);
        define('AGENT_NEW_DEBT', $agent->user_account->depts);

        define('ENVOY_CURRENT_PROFIT', $envoy->user_account->referral_commissions);
        define('ENVOY_NEW_PROFIT', ENVOY_CURRENT_PROFIT);

        define('ENVOY_CURRENT_DEBT', $envoy->user_account->depts);
        define('ENVOY_NEW_DEBT', Auth::user()->hasRole('envoy') ? (ENVOY_CURRENT_DEBT + $agentDeposit->amount) : (ENVOY_CURRENT_DEBT));

        $laravelTime = config('app.timezone');
        Carbon::setTestNow(Carbon::now($laravelTime));

         function updateAgentPercentage($agent) {
            $capital = $agent->user_account->capital;
            $percentage = $agent->default_percentage;
            switch (true) {
                case ($capital <= 19999):
                    if ($percentage < 25) {
                        $percentage = 25;
                    }
                    break;
                case ($capital <= 39999):
                    if ($percentage < 30) {
                        $percentage = 30;
                    }
                    break;
                case ($capital <= 59999):
                    if ($percentage < 35) {
                        $percentage = 35;
                    }
                    break;
                case ($capital <= 79999):
                    if ($percentage < 40) {
                        $percentage = 40;
                    }
                    break;
                case ($capital <= 99999):
                    if ($percentage < 45) {
                        $percentage = 45;
                    }
                    break;
                case ($capital >= 100000):
                    if ($percentage < 50) {
                        $percentage = 50;
                    }
                    break;
                // default:
                // if ($percentage < 45) {
                //     $percentage = 45;
                // }
                //     break;
            }
            $agent->update(['percentage' => $percentage]);
        }                

        switch (Auth::check()) {
            case Auth::user()->hasRole('envoy'):
                switch (true) {
                    case ($agentDeposit->status == 'pending' && $agentDeposit->confirmed_by_envoy == 0):
                        try {
                            DB::beginTransaction();
                            $agentDeposit->update(['confirmed_by_envoy' => 1]);
                            $agentDeposit->update(['envoy_confirmation_date' => Carbon::now($laravelTime)]);

                            // Set Envoy debt
                            $envoy->user_account->update(['depts' => ENVOY_NEW_DEBT]);
                            //Create Envent
                            event(new AgentDepositEvent($agentDeposit, $agent));
                            DB::commit();
                        } catch (\Exception $e) {
                            DB::rollBack();
                            return response()->json(['error' => 'An error occurred during the deposit confirmation process. Please try again. Error: ' . $e->getMessage()], 500);
                        }
                        switch ($agentDeposit->type) {
                            case $agentDeposit->type == 'user_account':
                                try {
                                    DB::beginTransaction();
                                    // Set Deposit History
                                    $agentDeposit_history = new AgentDepositActivit;
                                    $agentDeposit_history->amount = $agentDeposit->amount;
                                    $agentDeposit_history->status = $agentDeposit->status;
                                    $agentDeposit_history->type = $agentDeposit->type;
                                    $agentDeposit_history->currency = $agentDeposit->currency;
                                    $agentDeposit_history->envoy_id = $agentDeposit->envoy_id;
                                    $agentDeposit_history->user_id = $agentDeposit->user_id;
                                    $agentDeposit_history->sender_id = $agentDeposit->sender_id;
                                    $agentDeposit_history->receiver_id = $agentDeposit->receiver_id;
                                    $agentDeposit_history->user_account_id = $agentDeposit->user_account_id;
                                    $agentDeposit_history->system_account_id = $agentDeposit->systemAccount_id;
                                    $agentDeposit_history->initialization_date = $agentDeposit->initialization_date;
                                    $agentDeposit_history->agent_deposit_id = $agentDeposit->id;
                                    $agentDeposit_history->operation =  $agentDeposit->operation;
                                    $agentDeposit_history->confirmed_by_receiver = $agentDeposit->confirmed_by_receiver;
                                    $agentDeposit_history->confirmed_by_envoy = $agentDeposit->confirmed_by_envoy;
                                    $agentDeposit_history->envoy_confirmation_date = $agentDeposit->envoy_confirmation_date;

                                    $agentDeposit_history->current_agent_balance = AGENT_CURRENT_PROFIT;
                                    $agentDeposit_history->new_agent_balance = AGENT_NEW_PROFIT;
                                    $agentDeposit_history->current_agent_investment = AGENT_CURRENT_INVESTMENT;
                                    $agentDeposit_history->new_agent_investment = AGENT_NEW_INVESTMENT;

                                    $agentDeposit_history->current_agent_capital = $agent->user_account->capital;
                                    $agentDeposit_history->new_agent_capital = $agent->user_account->capital;

                                    $agentDeposit_history->current_agent_cash_in_hand = $agent->user_account->cash_in_hand;
                                    $agentDeposit_history->new_agent_cash_in_hand = $agent->user_account->cash_in_hand;

                                    $agentDeposit_history->current_envoy_balance = ENVOY_CURRENT_PROFIT;
                                    $agentDeposit_history->new_envoy_balance = ENVOY_NEW_PROFIT;
                                    $agentDeposit_history->current_envoy_debt = ENVOY_CURRENT_DEBT;
                                    $agentDeposit_history->new_envoy_debt = ENVOY_NEW_DEBT;
                                    $agentDeposit_history->current_system_fund = 0.0;
                                    $agentDeposit_history->new_system_fund = 0.0;
                                    $agentDeposit_history->step = Auth::user()->getRoleNames()->first() . '-confirmation';
                                    $agentDeposit_history->save();
                                    // Set deposit History End
                                    DB::commit();
                                } catch (\Exception $e) {
                                    DB::rollBack();
                                    return response()->json(['error' => 'An error occurred during the deposit confirmation process. Please try again. Error: ' . $e->getMessage()], 500);
                                }
                                break;

                            case $agentDeposit->type == 'user_bank_account':
                                # code...
                                break;
                        }
                    default:
                        # check if not confirmed by envoy yet...
                        break;
                }
                break;


            case (Auth::user()->hasRole('super-admin') || Auth::user()->hasRole('system-engineer')):
                switch ($agentDeposit) {
                    case ($agentDeposit->status == 'pending' && $agentDeposit->confirmed_by_receiver == 0 && $agentDeposit->confirmed_by_envoy == 1):
                        try {
                            DB::beginTransaction();
                            $agentDeposit->update(['confirmed_by_receiver' => 1]);
                            $agentDeposit->update(['receiver_confirmation_date' => Carbon::now($laravelTime)]);

                            //Create Envent
                            event(new AgentDepositEvent($agentDeposit, $agent));
                            DB::commit();
                        } catch (\Exception $e) {
                            DB::rollBack();
                            return response()->json(['error' => 'An error occurred during the deposit confirmation process. Please try again. Error: ' . $e->getMessage()], 500);
                        }
                        switch ($agentDeposit) {
                            case ($agentDeposit->confirmed_by_receiver == 1 && $agentDeposit->confirmed_by_envoy == 1):
                                switch ($agentDeposit->type) {
                                    case $agentDeposit->type == 'user_account':
                                        try {
                                            DB::beginTransaction();
                                            $agent->user_account->update(['investments' => AGENT_NEW_INVESTMENT]);

                                            
                                            if ($agentDeposit->amount < $agent->user_account->cash_in_hand) {
                                                $new_capital = $agent->user_account->cash_in_hand - $agentDeposit->amount;
                                                $agent->user_account->update(['capital' => $agent->user_account->capital - $new_capital,
                                                                             'cash_in_hand' => 0]);
                                                updateAgentPercentage($agent);
                                            }elseif($agentDeposit->amount > $agent->user_account->cash_in_hand){
                                                $new_capital = $agentDeposit->amount - $agent->user_account->cash_in_hand;
                                                $agent->user_account->update(['capital' => $agent->user_account->capital + $new_capital,
                                                                              'cash_in_hand' => 0]);
                                                updateAgentPercentage($agent);
                                            }else{
                                                $agent->user_account->update(['cash_in_hand' => 0]);
                                                updateAgentPercentage($agent);
                                            }
                                            
                                            //Update agent_deposit
                                            $agentDeposit->update(['current_balance' => AGENT_CURRENT_PROFIT,
                                                                    'new_balance' => AGENT_NEW_PROFIT,
                                                                    'current_depts' => AGENT_CURRENT_DEBT,
                                                                    'new_depts' => AGENT_NEW_DEBT,
                                                                    'operation' => 'investment',
                                                                    'status' => 'confirmed']);
                                            // $agentDeposit->update(['new_balance' => AGENT_NEW_PROFIT]);
                                            // $agentDeposit->update(['current_depts' => AGENT_CURRENT_DEBT]);
                                            // $agentDeposit->update(['new_depts' => AGENT_NEW_DEBT]);
                                            // $agentDeposit->update(['operation' => 'investment']);
                                            // $agentDeposit->update(['status' => 'confirmed']);

                                            //Get latest history
                                            $lastest_history = AgentDepositActivit::latest()->first();

                                            //Set Deposit History;
                                            $agentDeposit_history = new AgentDepositActivit;
                                            $agentDeposit_history->amount = $agentDeposit->amount;
                                            $agentDeposit_history->status = $agentDeposit->status;
                                            $agentDeposit_history->type = $agentDeposit->type;
                                            $agentDeposit_history->currency = $agentDeposit->currency;
                                            $agentDeposit_history->envoy_id = $agentDeposit->envoy_id;
                                            $agentDeposit_history->user_id = $agentDeposit->user_id;
                                            $agentDeposit_history->sender_id = $agentDeposit->sender_id;
                                            $agentDeposit_history->receiver_id = $agentDeposit->receiver_id;
                                            $agentDeposit_history->user_account_id = $agentDeposit->user_account_id;
                                            $agentDeposit_history->system_account_id = $agentDeposit->systemAccount_id;
                                            $agentDeposit_history->initialization_date = $agentDeposit->initialization_date;
                                            $agentDeposit_history->agent_deposit_id = $agentDeposit->id;
                                            $agentDeposit_history->operation =  $agentDeposit->operation;
                                            $agentDeposit_history->confirmed_by_receiver = $agentDeposit->confirmed_by_receiver;
                                            $agentDeposit_history->confirmed_by_envoy = $agentDeposit->confirmed_by_envoy;
                                            $agentDeposit_history->envoy_confirmation_date = $agentDeposit->envoy_confirmation_date;
                                            $agentDeposit_history->receiver_confirmation_date = $agentDeposit->receiver_confirmation_date;
                                            $agentDeposit_history->confirmation_date = $agentDeposit->receiver_confirmation_date;

                                            $agentDeposit_history->current_agent_balance = AGENT_CURRENT_PROFIT;
                                            $agentDeposit_history->new_agent_balance = AGENT_NEW_PROFIT;

                                            $agentDeposit_history->current_agent_capital =  $lastest_history->current_agent_cash_in_hand;
                                            $agentDeposit_history->new_agent_capital = $agent->user_account->capital;

                                            $agentDeposit_history->current_agent_cash_in_hand = $lastest_history->current_agent_cash_in_hand;
                                            $agentDeposit_history->new_agent_cash_in_hand = $agent->user_account->cash_in_hand;

                                            $agentDeposit_history->current_agent_investment = AGENT_CURRENT_INVESTMENT;
                                            $agentDeposit_history->new_agent_investment = AGENT_NEW_INVESTMENT;
                                            $agentDeposit_history->current_envoy_balance = $lastest_history->current_envoy_balance;
                                            $agentDeposit_history->new_envoy_balance = $lastest_history->new_envoy_balance;
                                            $agentDeposit_history->current_envoy_debt = $lastest_history->current_envoy_debt;
                                            $agentDeposit_history->new_envoy_debt = $lastest_history->new_envoy_debt;
                                            $agentDeposit_history->current_system_fund = 0.0;
                                            $agentDeposit_history->new_system_fund = 0.0;
                                            $agentDeposit_history->step = Auth::user()->getRoleNames()->first() . '-confirmation';
                                            $agentDeposit_history->save();
                                            //Set deposit History End

                                            // Increase system funds
                                            $systemAccount = SystemAccount::first();
                                            $systemAccount->update(['funds' => $systemAccount->funds + $agentDeposit->amount]);
                                            $systemAccount->update(['agent_investments' => $systemAccount->agent_investments + $agentDeposit->amount]);

                                            $invest = new AgentInvestment;
                                            $invest->amount = $agentDeposit->amount;
                                            $invest->status = 'confirmed';
                                            $invest->type = $agentDeposit->type;
                                            $invest->currency = $agentDeposit->currency;
                                            $invest->current_balance = AGENT_CURRENT_PROFIT;
                                            $invest->new_balance = AGENT_NEW_PROFIT;
                                            $invest->current_depts = AGENT_CURRENT_DEBT;
                                            $invest->new_depts = AGENT_NEW_DEBT;
                                            $invest->current_investment = AGENT_CURRENT_INVESTMENT;
                                            $invest->new_investment = AGENT_NEW_INVESTMENT;
                                            $invest->agent_deposit_id = $agentDeposit->id;
                                            $invest->user_id = $agentDeposit->user_id;
                                            $invest->save();
                                            DB::commit();
                                        } catch (\Exception $e) {
                                            DB::rollBack();
                                            return response()->json(['error' => 'An error occurred during the deposit confirmation process. Please try again. Error: ' . $e->getMessage()], 500);
                                        }
                                        break;

                                    case $agentDeposit->type == 'user_bank_account':
                                        # code...
                                        break;

                                    default:
                                        # code...
                                        break;
                                }
                                break;

                                // default:
                                //     # code...
                                //     break;
                        }
                        break;

                    default:
                        # check if confirmed by envoy already...
                        break;
                }
                break;

            default:
                # check if auth user is an envoy or super-admin
                break;
        }
    }
}
