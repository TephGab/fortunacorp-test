<?php

namespace App\Services\AgentLoanRepay;

use Carbon\Carbon;
use App\Models\AgentLoanRepay;
// use App\Events\AgentLoanRepayEvent;
use Illuminate\Support\Facades\DB;
use App\Models\AgentLoanRepayActivit;
use Illuminate\Support\Facades\Auth;
use App\Rules\CheckifAmountNotEqualZero;

class AgentLoanRepayStoreService
{
    public function agentLoanRepayStore($request, $user, $envoy, $systemAccount)
    {
        define('AGENT_CURRENT_PROFIT', $user->user_account->profits);
        define('AGENT_NEW_PROFIT', AGENT_CURRENT_PROFIT);

        define('AGENT_CURRENT_INVESTMENT', $user->user_account->investments);
        define('AGENT_NEW_INVESTMENT', AGENT_CURRENT_INVESTMENT);

        define('AGENT_CURRENT_DEBT', $user->user_account->depts);
        define('AGENT_NEW_DEBT', AGENT_CURRENT_DEBT);

        define('ENVOY_CURRENT_PROFIT', $envoy->user_account->profits);
        define('ENVOY_NEW_PROFIT', ENVOY_CURRENT_PROFIT);

        define('ENVOY_CURRENT_DEBT', $envoy->user_account->depts);
        define('ENVOY_NEW_DEBT', ENVOY_CURRENT_DEBT);

        $laravelTime = config('app.timezone');
        Carbon::setTestNow(Carbon::now($laravelTime));

        switch ($request->deposit_type) {
            case $request->deposit_type == 'user_account':
                $request->validate([
                    'envoy_id' => 'required',
                    'amount' => [
                        'required',
                        new CheckifAmountNotEqualZero()
                    ],
                ]);

                try {
                    DB::beginTransaction();
                    // Store Agent deposit
                    $deposit = new AgentLoanRepay;
                    $deposit->amount = $request->amount;
                    $deposit->status = 'pending';
                    $deposit->type = $request->deposit_type;
                    $deposit->currency = $request->deposit_currency;
                    $deposit->envoy_id = $request->envoy_id;
                    $deposit->user_id = $user->id;
                    $deposit->sender_id = Auth::id();
                    $deposit->receiver_id = 1;
                    $deposit->user_account_id = $user->user_account->id;
                    $deposit->system_account_id = $systemAccount->id;
                    $deposit->initialization_date = Carbon::now($laravelTime);
                    $deposit->operation = 'loan_repayment';
                    $deposit->loan_percentage = $user->loan_percentage;
                    $deposit->save();
                    // End Store Agent deposit
    
                    // Set Deposit History
                    $deposit_history = new AgentLoanRepayActivit;
                    $deposit_history->amount = $deposit->amount;
                    $deposit_history->status = $deposit->status;
                    $deposit_history->type = $deposit->type;
                    $deposit_history->currency = $deposit->currency;
                    $deposit_history->envoy_id = $deposit->envoy_id;
                    $deposit_history->user_id = $deposit->user_id;
                    $deposit_history->sender_id = $deposit->sender_id;
                    $deposit_history->receiver_id = $deposit->receiver_id;
                    $deposit_history->user_account_id = $deposit->user_account_id;
                    $deposit_history->system_account_id = $deposit->systemAccount_id;
                    $deposit_history->initialization_date = $deposit->initialization_date;
                    $deposit_history->agent_loan_repay_id = $deposit->id;
                    $deposit_history->operation = $deposit->operation;
                    $deposit_history->loan_percentage = $deposit->loan_percentage;
    
                    $deposit_history->current_agent_balance = AGENT_CURRENT_PROFIT;
                    $deposit_history->new_agent_balance = AGENT_NEW_PROFIT;
                    $deposit_history->current_agent_investment = AGENT_CURRENT_INVESTMENT;
                    $deposit_history->new_agent_investment = AGENT_NEW_INVESTMENT;
                    $deposit_history->current_agent_debt = AGENT_CURRENT_DEBT;
                    $deposit_history->new_agent_debt = AGENT_NEW_DEBT;
                    $deposit_history->current_envoy_balance = ENVOY_CURRENT_PROFIT;
                    $deposit_history->new_envoy_balance = ENVOY_NEW_PROFIT;
                    $deposit_history->current_envoy_debt = ENVOY_CURRENT_DEBT;
                    $deposit_history->new_envoy_debt = ENVOY_NEW_DEBT;
                    $deposit_history->current_system_fund = 0.0;
                    $deposit_history->new_system_fund = 0.0;
                    $deposit_history->step = 'initialization';
                    $deposit_history->save();
                    // Set deposit History End

                    DB::commit();
                    //Create Envent
                    //event(new AgentLoanRepayEvent($deposit, $user));

                    return response()->json(['message' => 'Deposit success'], 200);

                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json(['error' => 'An error occurred during the deposit process. Please try again. Error: ' . $e->getMessage()], 500);
                }
                break;

            case $request->deposit_type == 'user_bank_account':
                    # code...
                break;
            
            default:
                # code...
                break;
        }
    }
}