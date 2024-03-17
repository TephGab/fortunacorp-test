<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Replenishment;
use App\Models\SystemAccount;
use App\Models\SystemBankAccount;
use App\Events\ReplenishmentEvent;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Rules\ValidateReplenishment;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ReplenishmentRequest;
use App\Models\ReplenishmentRequirement;
use App\Models\Transaction;

class ReplenishmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->hasRole('super-admin')) {
            // DB::table('replenishments')
            return Replenishment::leftJoin('users as receiver', 'replenishments.receiver_id', '=', 'receiver.id')
            ->leftJoin('users as sender', 'replenishments.sender_id', '=', 'sender.id')
            ->leftJoin('users as envoy', 'replenishments.envoy_id', '=', 'envoy.id')
            ->select(
                'replenishments.*',
                // Receiver infos
                'receiver.id as receiver_id',
                'receiver.code as receiver_code',
                'receiver.first_name as receiver_first_name',
                'receiver.last_name as receiver_last_name',
                'receiver.email as receiver_email',
                //Sender infos
                'sender.id as sender_id',
                'sender.code as sender_code',
                'sender.first_name as sender_first_name',
                'sender.last_name as sender_last_name',
                'sender.email as sender_email',
                //Envoy infos
                'envoy.id as envoy_id',
                'envoy.code as envoy_code',
                'envoy.first_name as envoy_first_name',
                'envoy.last_name as envoy_last_name',
                'envoy.email as envoy_email',
            )->orderBy('created_at', 'DESC')->paginate(50);
        }
        if (Auth::user()->hasRole('agent')) {
                return Replenishment::leftJoin('users as receiver', 'replenishments.receiver_id', '=', 'receiver.id')
                    ->leftJoin('users as sender', 'replenishments.sender_id', '=', 'sender.id')
                    ->leftJoin('users as envoy', 'replenishments.envoy_id', '=', 'envoy.id')
                    ->where('replenishments.receiver_id', '=', Auth::id())
                    ->select(
                        'replenishments.*',
                        // Receiver infos
                        'receiver.id as receiver_id',
                        'receiver.code as receiver_code',
                        'receiver.first_name as receiver_first_name',
                        'receiver.last_name as receiver_last_name',
                        'receiver.email as receiver_email',
                        //Sender infos
                        'sender.id as sender_id',
                        'sender.code as sender_code',
                        'sender.first_name as sender_first_name',
                        'sender.last_name as sender_last_name',
                        'sender.email as sender_email',
                        //Envoy infos
                        'envoy.id as envoy_id',
                        'envoy.code as envoy_code',
                        'envoy.first_name as envoy_first_name',
                        'envoy.last_name as envoy_last_name',
                        'envoy.email as envoy_email',
                    )->orderBy('created_at', 'DESC')->paginate(50);
        }
        if (Auth::user()->hasRole('envoy')) {
            return Replenishment::leftJoin('users as receiver', 'replenishments.receiver_id', '=', 'receiver.id')
            ->leftJoin('users as sender', 'replenishments.sender_id', '=', 'sender.id')
            ->leftJoin('users as envoy', 'replenishments.envoy_id', '=', 'envoy.id')
            ->where('replenishments.envoy_id', '=', Auth::id())
            ->select(
                'replenishments.*',
                // Receiver infos
                'receiver.id as receiver_id',
                'receiver.code as receiver_code',
                'receiver.first_name as receiver_first_name',
                'receiver.last_name as receiver_last_name',
                'receiver.email as receiver_email',
                //Sender infos
                'sender.id as sender_id',
                'sender.code as sender_code',
                'sender.first_name as sender_first_name',
                'sender.last_name as sender_last_name',
                'sender.email as sender_email',
                //Envoy infos
                'envoy.id as envoy_id',
                'envoy.code as envoy_code',
                'envoy.first_name as envoy_first_name',
                'envoy.last_name as envoy_last_name',
                'envoy.email as envoy_email',
            )->orderBy('created_at', 'DESC')->paginate(50);
           // return Replenishment::where('envoy_id', Auth::id())->orderBy('created_at', 'DESC')->paginate(50);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ReplenishmentRequest $request)
    {
        $agent = User::with(['user_account', 'user_bank_accounts'])->findOrFail($request->agent_id);
        $systemAccount = SystemAccount::first();
        $systemBankAccount = SystemBankAccount::first();

        $laravelTimezone = config('app.timezone');
        Carbon::setTestNow(Carbon::now($laravelTimezone));

        if ($request->replenishment_type == 'user_account') {
            //Validate replemishment
            $request->validate([
                'amount' => new ValidateReplenishment($systemAccount->funds, $systemAccount->revenues),
            ]);

            if($systemAccount->funds >= $request->amount){
                $systemAccount->update(['funds' => ($systemAccount->funds - $request->amount)]);

                $replenishment = new Replenishment;
                $replenishment->type = $request->replenishment_type;
                $replenishment->sender_id = Auth::id();
                $replenishment->receiver_id = $request->agent_id;
                $replenishment->envoy_id = $request->envoy_id;
                $replenishment->amount = $request->amount;
                $replenishment->currency = $request->currency;
                $replenishment->status = 'pending';
                $replenishment->initialization_date = Carbon::now($laravelTimezone);
                $replenishment->user_account_id = $agent->user_account->id;
                $replenishment->system_account_id = $systemAccount->id;
                $replenishment->required_replenishment_id = $request->req_replenishment_id;
                $replenishment->isAreqReplenish = $request->isAreqReplenish;
                $replenishment->save();

                event(new ReplenishmentEvent($agent, $replenishment));
            }
        }
        if ($request->replenishment_type == 'user_bank_account') {
            $replenishment = new Replenishment;
            $replenishment->type = $request->replenishment_type;
            $replenishment->sender_id = Auth::id();
            $replenishment->receiver_id = $request->agent_id;
            $replenishment->envoy_id = $request->envoy_id;
            $replenishment->amount = $request->amount;
            $replenishment->currency = $request->currency;
            $replenishment->status = 'pending';
            $replenishment->initialization_date = Carbon::now($laravelTimezone);
            $replenishment->user_bank_account_id = $agent->user_bank_accounts->id;
            $replenishment->system_bank_account_id = $systemBankAccount->id;
            $replenishment->save();

            event(new ReplenishmentEvent($agent, $replenishment));
        }

        //$user = Auth::user();
           
        // event(new ReplenishmentEvent($replenishment, $agent));
    }

    public function confirmReplenishment(Request $request)
    {
        $replenishment = Replenishment::findOrFail($request->replenishment_id);
        $receiver = User::with(['user_account', 'user_bank_accounts'])->findOrFail($request->agent_id);

        //return $receiver;

        $laravelTimezone = config('app.timezone');
        Carbon::setTestNow(Carbon::now($laravelTimezone));

        if (Auth::user()->hasRole('agent')) {
            if($replenishment->status == 'pending' && $replenishment->confirmed_by_receiver == 0){
                $replenishment->update(['confirmed_by_receiver' => 1]);
                $replenishment->update(['receiver_confirmation_date' => Carbon::now($laravelTimezone)]);
                event(new ReplenishmentEvent($receiver, $replenishment));
                if($replenishment->confirmed_by_receiver == 1 && $replenishment->confirmed_by_envoy == 1){
                    $replenishment->update(['status' => 'confirmed']);
                    $receiver->user_account->update(['replenishments' => ($receiver->user_account->replenishments + $replenishment->amount)]);
                    //$receiver->user_account->update(['depts' => ($receiver->user_account->depts + $replenishment->amount)]);

                    // Req
                    if ($replenishment->isAreqReplenish) {
                        //return $replenishment->required_replenishment_id.'yeeee';
                        $req_replenishment = ReplenishmentRequirement::findOrFail($replenishment->required_replenishment_id);
                        $req_replenishment->update(['status' => 'confirmed']);

                         //Retreive Operator
                        $operator = User::leftJoin('transactions', function ($join) {
                            $join->on('users.id', '=', 'transactions.operator_id')
                            ->where('transactions.operator_id', '!=', 0);
                        })->select('users.id')
                            ->where('online_status', '=', 'online')
                            ->orWhere(function ($query) {
                            $query->groupBy('users.id')->havingRaw('COUNT(transactions.id) >= 0');
                        })->whereHas('roles', function ($query) {
                            $query->where('name', 'operator');
                        })->orderByRaw('(SELECT COUNT(transactions.id) FROM transactions WHERE transactions.operator_id = users.id AND transactions.status = "pending") ASC')->first();
                        
                        // return $operator;
                        $transaction = Transaction::findOrFail($req_replenishment->transaction_id);
                        if($operator){
                            $transaction->update(['operator_id' => $operator->id]);
                        }
                        else{
                            $transaction->update(['operator_id' => 1]);
                        }
                    }
    
                    // End Req
                }
            }
        }
        if (Auth::user()->hasRole('envoy')) {
            if($replenishment->status == 'pending' && $replenishment->confirmed_by_envoy == 0){
                $replenishment->update(['confirmed_by_envoy' => 1]);
                $replenishment->update(['envoy_confirmation_date' => Carbon::now($laravelTimezone)]);
                event(new ReplenishmentEvent($receiver, $replenishment));
                if($replenishment->confirmed_by_receiver == 1 && $replenishment->confirmed_by_envoy == 1){
                    $replenishment->update(['status' => 'confirmed']);
                    $receiver->user_account->update(['replenishments' => ($receiver->user_account->replenishments + $replenishment->amount)]);
                    //$receiver->user_account->update(['depts' => ($receiver->user_account->depts + $replenishment->amount)]);

                    // Req
                    if ($replenishment->isAreqReplenish) {
                        $req_replenishment = ReplenishmentRequirement::findOrFail($replenishment->required_replenishment_id);
                        $req_replenishment->update(['status' => 'confirmed']);
    
                         //Retreive Operator
                        $operator = User::leftJoin('transactions', function ($join) {
                            $join->on('users.id', '=', 'transactions.operator_id')
                            ->where('transactions.operator_id', '!=', 0);
                        })->select('users.id')
                            ->where('online_status', '=', 'online')
                            ->orWhere(function ($query) {
                            $query->groupBy('users.id')->havingRaw('COUNT(transactions.id) >= 0');
                        })->whereHas('roles', function ($query) {
                            $query->where('name', 'operator');
                        })->orderByRaw('(SELECT COUNT(transactions.id) FROM transactions WHERE transactions.operator_id = users.id AND transactions.status = "pending") ASC')->first();
                        
                        $transaction = Transaction::findOrFail($req_replenishment->transaction_id);
                        if($operator){
                            $transaction->update(['operator_id' => $operator->id]);
                        }
                        else{
                            $transaction->update(['operator_id' => 1]);
                        }
                    }
    
                    // End Req
                }
            }
        }

        return response()->json($replenishment);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Replenishment  $replenishment
     * @return \Illuminate\Http\Response
     */
    public function show(Replenishment $replenishment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Replenishment  $replenishment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Replenishment $replenishment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Replenishment  $replenishment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Replenishment $replenishment)
    {
        //
    }
}
