<?php

namespace App\Services\SendMoneys;

use Carbon\Carbon;
use App\Models\SendMoney;
use App\Models\SendMoneyActivit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Rules\CheckIfAmountLesThenRecharge;

class SendMoneyConfirmService
{
    public function sendMoneyConfirm($request, $receiver, $envoy, $sendmoney)
    {
        define('AGENT_CURRENT_PROFIT', $receiver->user_account->profits);
        define('AGENT_NEW_PROFIT', AGENT_CURRENT_PROFIT);

        define('AGENT_CURRENT_INVESTMENT', $receiver->user_account->investments);
        define('AGENT_NEW_INVESTMENT', (AGENT_CURRENT_INVESTMENT - $sendmoney->amount));

        define('AGENT_CURRENT_CASH_IN_HAND', $receiver->user_account->cash_in_hand);
        define('AGENT_NEW_CASH_IN_HAND', (AGENT_CURRENT_CASH_IN_HAND + $sendmoney->amount));

        define('ENVOY_CURRENT_PROFIT', $envoy->user_account->profits);
        define('ENVOY_NEW_PROFIT', ENVOY_CURRENT_PROFIT);

        define('ENVOY_CURRENT_DEBT', $envoy->user_account->depts);
        define('ENVOY_NEW_DEBT', $envoy->user_account->depts - $sendmoney->amount);
        //define('ENVOY_NEW_DEBT', $envoy->user_account->depts);

        $laravelTime = config('app.timezone');
        Carbon::setTestNow(Carbon::now($laravelTime));

        switch (Auth::check()) {
            case (Auth::user()->hasRole('agent')):
                switch (true) {
                    case $sendmoney->status == 'pending' && $sendmoney->confirmed_by_receiver == 0:
                        try {
                            DB::beginTransaction();
                            $sendmoney->update(['confirmed_by_receiver' => 1]);
                            $sendmoney->update(['receiver_confirmation_date' => Carbon::now($laravelTime)]);

                            // event(new ReplenishmentEvent($receiver, $sendmoney));
                            if ($sendmoney->confirmed_by_receiver == 1 && $sendmoney->confirmed_by_envoy == 1) {
                                $sendmoney->update(['status' => 'confirmed']);
                                $receiver->user_account->update(['investments' => AGENT_NEW_INVESTMENT,
                                                                 'cash_in_hand' => AGENT_NEW_CASH_IN_HAND]);
                                $envoy->user_account->update(['depts' => ENVOY_NEW_DEBT]);
                            }

                            // Set Send money history
                            $sendmoney_history = new SendMoneyActivit;
                            $sendmoney_history->type = $sendmoney->type;
                            $sendmoney_history->sender_id = $sendmoney->sender_id;
                            $sendmoney_history->receiver_id = $sendmoney->receiver_id;
                            $sendmoney_history->envoy_id = $sendmoney->envoy_id;
                            $sendmoney_history->amount = $sendmoney->amount;
                            $sendmoney_history->currency = $sendmoney->currency;
                            $sendmoney_history->status = $sendmoney->status;
                            $sendmoney_history->initialization_date =$sendmoney->initialization_date;
                            $sendmoney_history->user_account_id = $sendmoney->user_account_id;
                            $sendmoney_history->system_account_id = $sendmoney->system_account_id;
                            $sendmoney_history->use_envoy_debt = $sendmoney->use_envoy_debt;
                            $sendmoney_history->send_money_id = $sendmoney->id;
                            $sendmoney_history->confirmed_by_receiver = $sendmoney->confirmed_by_receiver;
                            // $sendmoney_history->confirmed_by_receiver = $sendmoney->confirmed_by_receiver;
                            $sendmoney_history->confirmed_by_envoy = $sendmoney->confirmed_by_envoy;
                            $sendmoney_history->receiver_confirmation_date = $sendmoney->receiver_confirmation_date;
                            $sendmoney_history->envoy_confirmation_date = $sendmoney->envoy_confirmation_date;

                            $sendmoney_history->current_agent_balance = AGENT_CURRENT_PROFIT;
                            $sendmoney_history->new_agent_balance = AGENT_NEW_PROFIT;

                            $sendmoney_history->current_agent_cash_in_hand = AGENT_NEW_CASH_IN_HAND - $sendmoney->amount;
                            $sendmoney_history->new_agent_cash_in_hand = AGENT_NEW_CASH_IN_HAND;

                            $sendmoney_history->current_agent_investment = AGENT_CURRENT_INVESTMENT;
                            $sendmoney_history->new_agent_investment = AGENT_NEW_INVESTMENT;
                            $sendmoney_history->current_envoy_balance = ENVOY_CURRENT_PROFIT;
                            $sendmoney_history->new_envoy_balance = ENVOY_NEW_PROFIT;
                            $sendmoney_history->current_envoy_debt = ENVOY_CURRENT_DEBT;
                            $sendmoney_history->new_envoy_debt = ENVOY_NEW_DEBT;
                            $sendmoney_history->current_system_fund = 0.0;
                            $sendmoney_history->new_system_fund = 0.0;
                            $sendmoney_history->step = Auth::user()->getRoleNames()->first() . '-confirmation';
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
            case (Auth::user()->hasRole('envoy')):
                switch (true) {
                    case $sendmoney->status == 'pending' && $sendmoney->confirmed_by_envoy == 0:
                        try {
                            DB::beginTransaction();
                            $sendmoney->update(['confirmed_by_envoy' => 1]);
                            $sendmoney->update(['envoy_confirmation_date' => Carbon::now($laravelTime)]);
                            $envoy->user_account->update(['depts' => ENVOY_CURRENT_DEBT]);

                            // Set Send money history
                            $sendmoney_history = new SendMoneyActivit;
                            $sendmoney_history->type = $sendmoney->type;
                            $sendmoney_history->sender_id = $sendmoney->sender_id;
                            $sendmoney_history->receiver_id = $sendmoney->receiver_id;
                            $sendmoney_history->envoy_id = $sendmoney->envoy_id;
                            $sendmoney_history->amount = $sendmoney->amount;
                            $sendmoney_history->currency = $sendmoney->currency;
                            $sendmoney_history->status = $sendmoney->status;
                            $sendmoney_history->initialization_date = Carbon::now($laravelTime);
                            $sendmoney_history->user_account_id = $receiver->user_account->id;
                            $sendmoney_history->system_account_id = $sendmoney->system_account_id;
                            $sendmoney_history->use_envoy_debt = $sendmoney->use_envoy_debt;
                            $sendmoney_history->send_money_id = $sendmoney->id;
                            $sendmoney_history->confirmed_by_receiver = $sendmoney->confirmed_by_receiver;
                            // $sendmoney_history->confirmed_by_receiver = $sendmoney->confirmed_by_receiver;
                            $sendmoney_history->confirmed_by_envoy = $sendmoney->confirmed_by_envoy;
                            $sendmoney_history->receiver_confirmation_date = $sendmoney->receiver_confirmation_date;
                            $sendmoney_history->envoy_confirmation_date = $sendmoney->envoy_confirmation_date;

                            $sendmoney_history->current_agent_balance = AGENT_CURRENT_PROFIT;
                            $sendmoney_history->new_agent_balance = AGENT_CURRENT_PROFIT;

                            $sendmoney_history->current_agent_cash_in_hand = AGENT_CURRENT_CASH_IN_HAND;
                            $sendmoney_history->new_agent_cash_in_hand = AGENT_CURRENT_CASH_IN_HAND;

                            $sendmoney_history->current_agent_investment = AGENT_CURRENT_INVESTMENT;
                            $sendmoney_history->new_agent_investment = AGENT_CURRENT_INVESTMENT;
                            $sendmoney_history->current_envoy_balance = ENVOY_CURRENT_PROFIT;
                            $sendmoney_history->new_envoy_balance = ENVOY_CURRENT_PROFIT;
                            $sendmoney_history->current_envoy_debt = ENVOY_CURRENT_DEBT;
                            $sendmoney_history->new_envoy_debt = ENVOY_CURRENT_DEBT;
                            $sendmoney_history->current_system_fund = 0.0;
                            $sendmoney_history->new_system_fund = 0.0;
                            $sendmoney_history->step = Auth::user()->getRoleNames()->first() . '-confirmation';
                            $sendmoney_history->save();
                            // End send money history
                            DB::commit();
                            return response()->json($sendmoney);
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

            default:
                # code...
                break;
        }
    }
}