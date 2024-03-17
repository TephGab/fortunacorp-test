<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\SystemAccount;
use App\Models\EnvoyTransfert;
use Illuminate\Support\Facades\DB;
use App\Events\EnvoyTransfertEvent;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\EnvoyTransfertActivit;
use App\Rules\CheckIfAmountLessThenDebt;

class EnvoyTransfertController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return EnvoyTransfert::with(['user', 'receiver'])->where('user_id', Auth::id())->orWhere('receiver_id', Auth::id())->paginate(50);
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
        $receiver = User::with(['user_account'])->findOrFail($request->envoy_id);
        $sumOfPendingAmounts = EnvoyTransfert::where('user_id', Auth::id())->where('status', 'pending')->sum('amount');
        $systemAccount = SystemAccount::findOrFail(1);

        $laravelTimezone = config('app.timezone');
        Carbon::setTestNow(Carbon::now($laravelTimezone));

        if ($request->transfert_type == 'user_account') {
            $request->validate([
                'amount' => [
                    'required',
                    new CheckIfAmountLessThenDebt($user->user_account->depts, $sumOfPendingAmounts)
                ],
                'envoy_id' => 'required',
            ]);
       
            try {
                DB::beginTransaction();

                $transfert = new EnvoyTransfert();
                $transfert->amount = $request->amount;
                $transfert->status = 'pending';
                $transfert->type = $request->transfert_type;
                $transfert->currency = $request->currency;
                $transfert->comment = $request->comment;
                $transfert->user_id = Auth::id();
                $transfert->receiver_id = $request->envoy_id;
                $transfert->confirmed_by_receiver = 0;
                $transfert->sender_current_depts = $user->user_account->depts;
                $transfert->receiver_current_depts = $receiver->user_account->depts;
                $transfert->save();

                $transfert_history = new EnvoyTransfertActivit();
                $transfert_history->amount = $transfert->amount;
                $transfert_history->status = $transfert->status;
                $transfert_history->type = $transfert->type;
                $transfert_history->currency = $transfert->currency;
                $transfert_history->comment = $request->comment;
                $transfert_history->user_id = $transfert->user_id;
                $transfert_history->receiver_id = $transfert->receiver_id;
                $transfert_history->confirmed_by_receiver = $transfert->confirmed_by_receiver;
                $transfert_history->sender_current_depts = $transfert->sender_current_depts;
                $transfert_history->receiver_current_depts = $transfert->receiver_current_depts;
                $transfert_history->envoy_transfert_id = $transfert->id;
            
                $transfert_history->current_sender_debt = $user->user_account->depts;
                $transfert_history->current_sender_referral_commission = $user->user_account->referral_commissions;

                $transfert_history->current_receiver_debt = $receiver->user_account->depts;
                $transfert_history->current_receiver_referral_commission = $receiver->user_account->referral_commissions;
              
                $transfert_history->current_system_claim = 0.0;

                $transfert_history->current_system_fund = 0.0;
                $transfert_history->new_system_fund = 0.0;
                $transfert_history->step = 'initialization';
                $transfert_history->save();

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['error' => 'An error occurred during the transfert process. Please try again. Error: ' . $e->getMessage()], 500);
            }
            //Create Envent
            event(new EnvoyTransfertEvent($transfert, $user));

        }
        else if ($request->transfert_type == 'user_bank_account') {
          //
        }

        //Create Envent
        //event(new EnvoyDepositEvent($transfert, $user));

        return response()->json(['message' => 'Transfert success'], 200); 
    }


    public function confirmEnvoyTransfert(Request $request)
    {
        if (Auth::user()->hasRole('envoy') ) {
            $transfert = EnvoyTransfert::findOrFail($request->transfert_id);
            $sender = User::with(['user_account'])->findOrFail($transfert->user_id);
            $receiver = User::with(['user_account'])->findOrFail($transfert->receiver_id);
            $laravelTimezone = config('app.timezone');
            Carbon::setTestNow(Carbon::now($laravelTimezone));

            $new_sender_referral_commission = $sender->user_account->referral_commissions;
            $new_sender_debt = $sender->user_account->depts - $transfert->amount;

            $new_receiver_referral_commission = $receiver->user_account->referral_commissions;
            $new_receiver_debt = $receiver->user_account->depts + $transfert->amount;

            if ($transfert->confirmed_by_receiver == 0 && $receiver->id == Auth::id()) {
                try {
                    DB::beginTransaction();

                    $transfert->update(['confirmed_by_receiver' => 1]);
                    $transfert->update(['receiver_confirmation_date' => Carbon::now($laravelTimezone)]);
                    $transfert->update(['status' => 'confirmed']);

                    $transfert_history = new EnvoyTransfertActivit();
                    $transfert_history->amount = $transfert->amount;
                    $transfert_history->status = $transfert->status;
                    $transfert_history->type = $transfert->type;
                    $transfert_history->currency = $transfert->currency;
                    $transfert_history->comment = $request->comment;
                    $transfert_history->user_id = $transfert->user_id;
                    $transfert_history->receiver_id = $transfert->receiver_id;
                    $transfert_history->confirmed_by_receiver = $transfert->confirmed_by_receiver;
                    $transfert_history->sender_current_depts = $transfert->sender_current_depts;
                    $transfert_history->receiver_current_depts = $transfert->receiver_current_depts;
                    $transfert_history->envoy_transfert_id = $transfert->id;

                    $transfert_history->current_sender_debt = $transfert->sender_current_depts;
                    $transfert_history->current_sender_referral_commission = $sender->user_account->referral_commissions;
    
                    $transfert_history->current_receiver_debt = $transfert->receiver_current_depts;
                    $transfert_history->current_receiver_referral_commission = $receiver->user_account->referral_commissions;
                
                    $transfert_history->new_sender_debt = $new_sender_debt;
                    $transfert_history->new_sender_referral_commission = $new_sender_referral_commission;
    
                    $transfert_history->new_receiver_debt = $new_receiver_debt;
                    $transfert_history->new_receiver_referral_commission = $new_receiver_referral_commission;
                  
                    $transfert_history->current_system_claim = 0.0;
    
                    $transfert_history->current_system_fund = 0.0;
                    $transfert_history->new_system_fund = 0.0;
                    $transfert_history->step = 'confirmation';
                    $transfert_history->save();

                    $transfert->update(['sender_new_depts' => $new_sender_debt]);
                    $transfert->update(['receiver_new_depts' => $new_receiver_debt]);

                    $sender->user_account->update(['depts' => $new_sender_debt]);
                    $receiver->user_account->update(['depts' => $new_receiver_debt]);
    
                    DB::commit();
                    return response()->json(['message' => 'Envoy transfert confirmation success'], 200);
                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json(['error' => 'An error occurred during the tra$transfert process. Please try again. Error: ' . $e->getMessage()], 500);
                }
            }

            //Create Envent
            event(new EnvoyTransfertEvent($transfert, $sender));
            
            return EnvoyTransfert::with(['user', 'receiver'])->orderBy('created_at', 'DESC')->paginate(50);
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EnvoyTransfert  $envoyTransfert
     * @return \Illuminate\Http\Response
     */
    public function show(EnvoyTransfert $envoyTransfert)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EnvoyTransfert  $envoyTransfert
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EnvoyTransfert $envoyTransfert)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EnvoyTransfert  $envoyTransfert
     * @return \Illuminate\Http\Response
     */
    public function destroy(EnvoyTransfert $envoyTransfert)
    {
        //
    }
}
