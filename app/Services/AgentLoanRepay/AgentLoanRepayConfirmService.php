<?php

namespace App\Services\AgentLoanRepay;

use Carbon\Carbon;
use App\Models\SystemAccount;
use Illuminate\Support\Facades\DB;
use App\Models\AgentLoanRepayActivit;
use Illuminate\Support\Facades\Auth;

class AgentLoanRepayConfirmService
{
    public function loanPaymentConfirm($loan, $agent, $envoy)
    {
        define('AGENT_CURRENT_PROFIT', $agent->user_account->profits);
        define('AGENT_NEW_PROFIT', AGENT_CURRENT_PROFIT);

        define('AGENT_CURRENT_INVESTMENT', $agent->user_account->investments);
        define('AGENT_NEW_INVESTMENT', AGENT_CURRENT_INVESTMENT);

        define('AGENT_CURRENT_DEBT', $agent->user_account->depts);
        define('AGENT_NEW_DEBT', Auth::user()->hasRole('envoy') ? AGENT_CURRENT_DEBT : ($agent->user_account->depts - $loan->amount));

        define('AGENT_CURRENT_CAPITAL', $agent->user_account->capital);
        define('AGENT_NEW_CAPITAL', Auth::user()->hasRole('envoy') ? AGENT_CURRENT_CAPITAL : ($agent->user_account->capital + $loan->amount));

        define('ENVOY_CURRENT_PROFIT', $envoy->user_account->referral_commissions);
        define('ENVOY_NEW_PROFIT', ENVOY_CURRENT_PROFIT);

        define('ENVOY_CURRENT_DEBT', $envoy->user_account->depts);
        define('ENVOY_NEW_DEBT', Auth::user()->hasRole('envoy') ? ($envoy->user_account->depts + $loan->amount) : (ENVOY_CURRENT_DEBT));

        $laravelTime = config('app.timezone');
        Carbon::setTestNow(Carbon::now($laravelTime));

        switch (Auth::check()) {
            case Auth::user()->hasRole('envoy'):
                switch (true) {
                    case ($loan->status == 'pending' && $loan->confirmed_by_envoy == false && $loan->type == 'user_account' && Auth::id() == $loan->envoy_id):
                        try {
                            DB::beginTransaction();

                            $loan->update(['confirmed_by_envoy' => true]);
                            $loan->update(['envoy_confirmation_date' => Carbon::now($laravelTime)]);

                            //Create Envent
                           // event(new AgentLoanRepayEvent($loan, $agent));

                             // Set Deposit History
                             $loan_history = new AgentLoanRepayActivit;
                             $loan_history->amount = $loan->amount;
                             $loan_history->status = $loan->status;
                             $loan_history->type = $loan->type;
                             $loan_history->currency = $loan->currency;
                             $loan_history->envoy_id = $loan->envoy_id;
                             $loan_history->user_id = $loan->user_id;
                             $loan_history->sender_id = $loan->sender_id;
                             $loan_history->receiver_id = $loan->receiver_id;
                             $loan_history->user_account_id = $loan->user_account_id;
                             $loan_history->system_account_id = $loan->systemAccount_id;
                             $loan_history->initialization_date = $loan->initialization_date;
                             $loan_history->agent_loan_repay_id = $loan->id;
                             $loan_history->operation = $loan->operation;
                             $loan_history->confirmed_by_receiver = $loan->confirmed_by_receiver;
                             $loan_history->confirmed_by_envoy = $loan->confirmed_by_envoy;
                             $loan_history->envoy_confirmation_date = $loan->envoy_confirmation_date;
                             $loan_history->loan_percentage = $loan->loan_percentage;

                             $loan_history->current_agent_balance = AGENT_CURRENT_PROFIT;
                             $loan_history->new_agent_balance = AGENT_NEW_PROFIT;
                             $loan_history->current_agent_investment = AGENT_CURRENT_INVESTMENT;
                             $loan_history->new_agent_investment = AGENT_NEW_INVESTMENT;
                             $loan_history->current_agent_debt = AGENT_CURRENT_DEBT;
                             $loan_history->new_agent_debt = AGENT_NEW_DEBT;

                            $loan_history->current_agent_capital = AGENT_CURRENT_CAPITAL;
                            $loan_history->new_agent_capital = AGENT_NEW_CAPITAL;

                             $loan_history->current_envoy_balance = ENVOY_CURRENT_PROFIT;
                             $loan_history->new_envoy_balance = ENVOY_NEW_PROFIT;
                             $loan_history->current_envoy_debt = ENVOY_CURRENT_DEBT;
                             $loan_history->new_envoy_debt = ENVOY_NEW_DEBT;
                             $loan_history->current_system_fund = 0.0;
                             $loan_history->new_system_fund = 0.0;
                             $loan_history->step = Auth::user()->getRoleNames()->first() . '-confirmation';
                             $loan_history->save();

                            // Set Envoy debt
                            $envoy->user_account->update(['depts' => ENVOY_NEW_DEBT]);
                            DB::commit();
                        } catch (\Exception $e) {
                            DB::rollBack();
                            return response()->json(['error' => 'An error occurred during the deposit confirmation process. Please try again. Error: ' . $e->getMessage()], 500);
                        }
                 
                    break;
                }
            break;


            case (Auth::user()->hasRole('super-admin') || Auth::user()->hasRole('system-engineer')):
                switch (true) {
                    case ($loan->status == 'pending' && $loan->confirmed_by_receiver == false && $loan->confirmed_by_envoy == true && $loan->type == 'user_account'):
                        try {
                            DB::beginTransaction();

                            $loan->update(['confirmed_by_receiver' => true]);
                            $loan->update(['receiver_confirmation_date' => Carbon::now($laravelTime)]);

                            //Create Envent
                            //event(new AgentLoanRepayEvent($loan, $agent));

                            // History
                            if ($loan->confirmed_by_receiver == true && $loan->confirmed_by_envoy == true){
                                switch ($loan->type) {
                                    case 'user_account':
                                        try {
                                            DB::beginTransaction();

                                            //Update agent_loan repayment
                                            $loan->update(['current_depts' => AGENT_CURRENT_DEBT,
                                                           'new_depts' => AGENT_NEW_DEBT,
                                                           'current_capital' => AGENT_CURRENT_CAPITAL,
                                                           'new_capital' => AGENT_NEW_CAPITAL,
                                                           'operation' => 'loan_repayment',
                                                           'status' => 'confirmed']);

                                             // Increase system funds
                                             $systemAccount = SystemAccount::first();
                                             $systemAccount->update(['funds' => $systemAccount->funds + $loan->amount]);

                                            // Dsicrease agebt dept
                                            $agent->user_account->update(['depts' => AGENT_NEW_DEBT,
                                                                         'capital' => AGENT_NEW_CAPITAL]);
                    
                                            //Get latest history
                                            $lastest_history = AgentLoanRepayActivit::latest()->first();

                                            //Set Deposit History;
                                            $loan_history = new AgentLoanRepayActivit;
                                            $loan_history->amount = $loan->amount;
                                            $loan_history->status = $loan->status;
                                            $loan_history->type = $loan->type;
                                            $loan_history->currency = $loan->currency;
                                            $loan_history->envoy_id = $loan->envoy_id;
                                            $loan_history->user_id = $loan->user_id;
                                            $loan_history->sender_id = $loan->sender_id;
                                            $loan_history->receiver_id = $loan->receiver_id;
                                            $loan_history->user_account_id = $loan->user_account_id;
                                            $loan_history->system_account_id = $loan->systemAccount_id;
                                            $loan_history->initialization_date = $loan->initialization_date;
                                            $loan_history->agent_loan_repay_id = $loan->id;
                                            $loan_history->operation = $loan->operation;
                                            $loan_history->confirmed_by_receiver = $loan->confirmed_by_receiver;
                                            $loan_history->confirmed_by_envoy = $loan->confirmed_by_envoy;
                                            $loan_history->envoy_confirmation_date = $loan->envoy_confirmation_date;
                                            $loan_history->receiver_confirmation_date = $loan->receiver_confirmation_date;
                                            $loan_history->loan_percentage = $loan->loan_percentage;

                                            $loan_history->current_agent_balance = AGENT_CURRENT_PROFIT;
                                            $loan_history->new_agent_balance = AGENT_NEW_PROFIT;
                                            $loan_history->current_agent_investment = AGENT_CURRENT_INVESTMENT;
                                            $loan_history->new_agent_investment = AGENT_NEW_INVESTMENT;
                                            $loan_history->current_agent_debt = AGENT_CURRENT_DEBT;
                                            $loan_history->new_agent_debt = AGENT_NEW_DEBT;

                                            $loan_history->current_agent_capital = AGENT_CURRENT_CAPITAL;
                                            $loan_history->new_agent_capital = AGENT_NEW_CAPITAL;
                                            
                                            $loan_history->current_envoy_balance = $lastest_history->current_envoy_balance;
                                            $loan_history->new_envoy_balance = $lastest_history->new_envoy_balance;
                                            $loan_history->current_envoy_debt = $lastest_history->current_envoy_debt;
                                            $loan_history->new_envoy_debt = $lastest_history->new_envoy_debt;
                                            $loan_history->current_system_fund = 0.0;
                                            $loan_history->new_system_fund = 0.0;
                                            $loan_history->step = Auth::user()->getRoleNames()->first() . '-confirmation';
                                            $loan_history->save();
                                            //Set deposit History End

                                            DB::commit();
                                        } catch (\Exception $e) {
                                            DB::rollBack();
                                            return response()->json(['error' => 'An error occurred during the deposit confirmation process. Please try again. Error: ' . $e->getMessage()], 500);
                                        }
                                        break;

                                    case 'user_bank_account':
                                        # code...
                                    break;

                                    default:
                                        # if not 'user_account nor 'user_bank_account
                                    break;
                                }
                            }
                            // End history
                            DB::commit();
                        } catch (\Exception $e) {
                            DB::rollBack();
                            return response()->json(['error' => 'An error occurred during the deposit confirmation process. Please try again. Error: ' . $e->getMessage()], 500);
                        }
                    break;
                }
            break;

            default:
            # check if auth user has other role
            break;
        }
    }
}