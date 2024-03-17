<?php

namespace App\Services\SendMoneys;

use Carbon\Carbon;
use App\Models\SendMoney;
use App\Models\SendMoneyActivit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Rules\CheckIfAmountLesThenRecharge;

class SendMoneyStoreService
{
    public function sendMoneyStore($request, $agent, $envoy, $systemAccount, $systemBankAccount)
    {
        define('AGENT_CURRENT_PROFIT', $agent->user_account->profits);
        define('AGENT_NEW_PROFIT', AGENT_CURRENT_PROFIT);

        define('AGENT_CURRENT_INVESTMENT', $agent->user_account->investments);
        define('AGENT_NEW_INVESTMENT', AGENT_CURRENT_INVESTMENT);

        define('ENVOY_CURRENT_PROFIT', $envoy->user_account->profits);
        define('ENVOY_NEW_PROFIT', ENVOY_CURRENT_PROFIT);

        define('ENVOY_CURRENT_DEBT', $envoy->user_account->depts);
        define('ENVOY_NEW_DEBT', ENVOY_CURRENT_DEBT);

        $laravelTime = config('app.timezone');
        Carbon::setTestNow(Carbon::now($laravelTime));

        switch ($request->send_money_type) {
            case $request->send_money_type == 'user_account':
                //Validate send money
                $request->validate([
                    'amount' => [
                        'required',
                        new CheckIfAmountLesThenRecharge($agent->user_account->investments),
                    ],
                ]);

                switch ($request->amount) {
                    case $request->amount <= $systemAccount->funds:
                        try {
                            DB::beginTransaction();
                            $systemAccount->update(['funds' => ($systemAccount->funds - $request->amount)]);

                            $sendmoney = new SendMoney();
                            $sendmoney->type = $request->send_money_type;
                            $sendmoney->sender_id = Auth::id();
                            $sendmoney->receiver_id = $request->agent_id;
                            $sendmoney->envoy_id = $request->envoy_id;
                            $sendmoney->amount = $request->amount;
                            $sendmoney->currency = $request->currency;
                            $sendmoney->status = 'pending';
                            $sendmoney->initialization_date = Carbon::now($laravelTime);
                            $sendmoney->user_account_id = $agent->user_account->id;
                            $sendmoney->system_account_id = $systemAccount->id;
                            $sendmoney->use_envoy_debt = $request->use_envoy_debt;
                            $sendmoney->save();
    
                            // Set Send money history
                            $sendmoney_history = new SendMoneyActivit();
                            $sendmoney_history->type = $request->send_money_type;
                            $sendmoney_history->sender_id = Auth::id();
                            $sendmoney_history->receiver_id = $request->agent_id;
                            $sendmoney_history->envoy_id = $request->envoy_id;
                            $sendmoney_history->amount = $request->amount;
                            $sendmoney_history->currency = $request->currency;
                            $sendmoney_history->status = 'pending';
                            $sendmoney_history->initialization_date = Carbon::now($laravelTime);
                            $sendmoney_history->user_account_id = $agent->user_account->id;
                            $sendmoney_history->system_account_id = $systemAccount->id;
                            $sendmoney_history->use_envoy_debt = $request->use_envoy_debt;
                            $sendmoney_history->send_money_id = $sendmoney->id;
    
                            $sendmoney_history->current_agent_balance = AGENT_CURRENT_PROFIT;
                            $sendmoney_history->new_agent_balance = AGENT_NEW_PROFIT;    
                            $sendmoney_history->current_agent_investment = AGENT_CURRENT_INVESTMENT;
                            $sendmoney_history->new_agent_investment = AGENT_NEW_INVESTMENT; 
                            $sendmoney_history->current_envoy_balance = ENVOY_CURRENT_PROFIT; 
                            $sendmoney_history->new_envoy_balance = ENVOY_NEW_PROFIT;
                            $sendmoney_history->current_envoy_debt = ENVOY_CURRENT_DEBT;
                            $sendmoney_history->new_envoy_debt = ENVOY_NEW_DEBT;
                            $sendmoney_history->current_system_fund = 0.0; 
                            $sendmoney_history->new_system_fund = 0.0;
                            $sendmoney_history->step = Auth::user()->getRoleNames()->first() .'-initilization';
                            $sendmoney_history->save();
                            // End send money history
                            DB::commit();
                        } catch (\Exception $e) {
                            DB::rollBack();
                            return response()->json(['error' => 'An error occurred during send money process. Please try again. Error: ' . $e->getMessage()], 500);
                        }
                        break;

                    default:
                        # code...
                        break;
                }
                break;
            case $request->send_money_type == 'user_bank_account': 
                try {
                    DB::beginTransaction();
                    // $sendmoney = new sendmoney;
                    // $sendmoney->type = $request->send_money_type;
                    // $sendmoney->sender_id = Auth::id();
                    // $sendmoney->receiver_id = $request->agent_id;
                    // $sendmoney->envoy_id = $request->envoy_id;
                    // $sendmoney->amount = $request->amount;
                    // $sendmoney->currency = $request->currency;
                    // $sendmoney->status = 'pending';
                    // $sendmoney->initialization_date = Carbon::now($laravelTime);
                    // $sendmoney->user_bank_account_id = $agent->user_bank_accounts->id;
                    // $sendmoney->system_bank_account_id = $systemBankAccount->id;
                    // $sendmoney->use_envoy_debt = $request->use_envoy_debt;
                    // $sendmoney->save();
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json(['error' => 'An error occurred during send money process. Please try again. Error: ' . $e->getMessage()], 500);
                } 
                break;
            default:
                # code...
                break;
        }
    }
}