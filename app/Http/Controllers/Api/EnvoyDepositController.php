<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use App\Models\EnvoyDeposit;
use Illuminate\Http\Request;
use App\Models\SystemAccount;
use App\Events\EnvoyDepositEvent;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\EnvoyDepositActivit;
use Illuminate\Support\Facades\Auth;
use App\Rules\CheckIfAmountLessThenDebt;

class EnvoyDepositController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->hasRole('super-admin') || Auth::user()->hasRole('system-engineer')) {
            return EnvoyDeposit::with(['user'])->orderBy('created_at', 'DESC')->paginate(50);
        }
        if (Auth::user()->hasRole('envoy')) {
            return EnvoyDeposit::with(['user'])->where('user_id', '=', Auth::id())->orderBy('created_at', 'DESC')->paginate(50);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = User::with(['user_account'])->findOrFail(Auth::id());
        $sumOfPendingAmounts = EnvoyDeposit::where('user_id', Auth::id())->where('status', 'pending')->sum('amount');
        $systemAccount = SystemAccount::findOrFail(1);

        $laravelTimezone = config('app.timezone');
        Carbon::setTestNow(Carbon::now($laravelTimezone));

        if ($request->deposit_type == 'user_account') {
            $request->validate([
                'amount' => [
                    'required',
                    new CheckIfAmountLessThenDebt($user->user_account->depts, $sumOfPendingAmounts)
                ],
            ]);
       
            try {
                DB::beginTransaction();

                $deposit = new EnvoyDeposit;
                $deposit->amount = $request->amount;
                $deposit->status = 'pending';
                $deposit->type = $request->deposit_type;
                $deposit->currency = $request->currency;
                $deposit->comment = $request->comment;
                $deposit->user_id = Auth::id();
                $deposit->user_account_id = $user->user_account->id;
                $deposit->receiver_id = 1;
                $deposit->confirmed_by_receiver = 0;
                $deposit->system_account_id = $systemAccount->id;
                $deposit->current_balance = $user->user_account->referral_commissions;
                $deposit->current_depts = $user->user_account->depts;
                $deposit->save();

                $deposit_history = new EnvoyDepositActivit();
                $deposit_history->amount = $deposit->amount;
                $deposit_history->status = $deposit->status;
                $deposit_history->type = $deposit->type;
                $deposit_history->currency = $deposit->currency;
                $deposit_history->comment = $request->comment;
                $deposit_history->user_id = $deposit->user_id;
                $deposit_history->user_account_id = $deposit->user_account_id;
                $deposit_history->receiver_id = $deposit->receiver_id;
                $deposit_history->confirmed_by_receiver = $deposit->confirmed_by_receiver;
                $deposit_history->system_account_id = $deposit->system_account_id;
                $deposit_history->current_balance = $deposit->current_balance;
                $deposit_history->current_depts = $deposit->current_depts;
                $deposit_history->envoy_deposit_id = $deposit->id;
            
                $deposit_history->current_envoy_balance = $user->user_account->referral_commissions;
                $deposit_history->new_envoy_balance = $user->user_account->referral_commissions;
                $deposit_history->current_envoy_debt = $user->user_account->depts;
                $deposit_history->new_envoy_debt = $user->user_account->depts;
                $deposit_history->current_system_fund = 0.0;
                $deposit_history->new_system_fund = 0.0;
                $deposit_history->step = 'initialization';
                $deposit_history->save();

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['error' => 'An error occurred during the deposit process. Please try again. Error: ' . $e->getMessage()], 500);
            }
            //Create Envent
            event(new EnvoyDepositEvent($deposit, $user));

        }
        else if ($request->deposit_type == 'user_bank_account') {
          //
        }

        //Create Envent
        event(new EnvoyDepositEvent($deposit, $user));

        return response()->json(['message' => 'Deposit success'], 200); 
    }

    public function confirmEnvoyDeposit(Request $request)
    {
        if (Auth::user()->hasRole('super-admin') || Auth::user()->hasRole('system-engineer')) {
            $deposit = EnvoyDeposit::findOrFail($request->deposit_id);
            $envoy = User::with(['user_account'])->findOrFail($deposit->user_id);
            $laravelTimezone = config('app.timezone');
            Carbon::setTestNow(Carbon::now($laravelTimezone));

            // $current_balance = $envoy->user_account->referral_commissions;
            $new_balance = $envoy->user_account->referral_commissions;
            // $current_depts = $envoy->user_account->depts;
            $new_depts = $envoy->user_account->depts - $deposit->amount;

            if ($deposit->confirmed_by_receiver == 0) {
                try {
                    DB::beginTransaction();

                    $deposit->update(['status' => 'confirmed']);
                    $deposit->update(['confirmed_by_receiver' => 1]);
                    $deposit->update(['receiver_confirmation_date' => Carbon::now($laravelTimezone)]);

                    $deposit->update(['new_balance' => $new_balance]);
                    $deposit->update(['new_depts' => $new_depts]);

                    $deposit_history = new EnvoyDepositActivit();
                    $deposit_history->amount = $deposit->amount;
                    $deposit_history->status = $deposit->status;
                    $deposit_history->type = $deposit->type;
                    $deposit_history->currency = $deposit->currency;
                    $deposit_history->comment = $request->comment;
                    $deposit_history->user_id = $deposit->user_id;
                    $deposit_history->user_account_id = $deposit->user_account_id;
                    $deposit_history->receiver_id = $deposit->receiver_id;
                    $deposit_history->confirmed_by_receiver = $deposit->confirmed_by_receiver;
                    $deposit_history->system_account_id = $deposit->system_account_id;
                    $deposit_history->current_balance = $deposit->current_balance;
                    $deposit_history->current_depts = $deposit->current_depts;
                    $deposit_history->envoy_deposit_id = $deposit->id;
                
                    $deposit_history->current_envoy_balance = $envoy->user_account->referral_commissions;
                    $deposit_history->new_envoy_balance = $envoy->user_account->referral_commissions;
                    $deposit_history->current_envoy_debt = $envoy->user_account->depts;
                    $deposit_history->new_envoy_debt = $new_depts;
                    $deposit_history->current_system_fund = 0.0;
                    $deposit_history->new_system_fund = 0.0;
                    $deposit_history->step = 'confirmation';
                    $deposit_history->save();
    
                    $envoy->user_account->update(['depts' => $new_depts]);

                    DB::commit();
                    return response()->json(['message' => 'Envoy deposit confirmation success'], 200);
                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json(['error' => 'An error occurred during the deposit process. Please try again. Error: ' . $e->getMessage()], 500);
                }
            }

              //Create Envent
            $envoy = User::with(['user_account'])->findOrFail($deposit->sender_id);
            event(new EnvoyDepositEvent($deposit, $envoy));
            
            return EnvoyDeposit::with(['user'])->orderBy('created_at', 'DESC')->paginate(50);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EnvoyDeposit  $envoyDeposit
     * @return \Illuminate\Http\Response
     */
    public function show(EnvoyDeposit $envoyDeposit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EnvoyDeposit  $envoyDeposit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EnvoyDeposit $envoyDeposit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EnvoyDeposit  $envoyDeposit
     * @return \Illuminate\Http\Response
     */
    public function destroy(EnvoyDeposit $envoyDeposit)
    {
        //
    }
}
