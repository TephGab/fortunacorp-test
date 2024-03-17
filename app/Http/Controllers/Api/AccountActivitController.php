<?php

namespace App\Http\Controllers\Api;

use App\Models\Account;
use Illuminate\Http\Request;
use App\Models\AccountActivit;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\AccountResource;

class AccountActivitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function getAccountActivity(Request $request)
    {
        $currentMonth = $request->selected_month;
        $currentYear = $request->selected_year;
        $accountId = $request->account_id;

        if (Auth::user()->hasRole('super-admin') || Auth::user()->hasRole('admin') || Auth::user()->hasRole('system-engineer') || Auth::user()->hasRole('operator')) {  
            $transactions = Account::select('transactions.*',
            'agent.code as agent_code',
            'agent.first_name as agent_first_name',
            'agent.last_name as agent_last_name',
            'operator.code as operator_code',
            'operator.first_name as operator_first_name',
            'operator.last_name as operator_last_name',
            'client.name as client_name',
            'receiver.name as receiver_name',)
            ->leftJoin('transactions', 'accounts.id', '=', 'transactions.account_id')
            ->leftJoin('users as agent', 'agent.id', '=', 'transactions.user_id')
            ->leftJoin('users as operator', 'operator.id', '=', 'transactions.operator_id')
            ->leftJoin('clients as client', 'client.id', '=', 'transactions.client_id')
            ->leftJoin('clients as receiver', 'receiver.id', '=', 'transactions.receiver')
            ->where('accounts.id', $accountId)
            ->whereMonth('transactions.created_at', $currentMonth)
            ->whereYear('transactions.created_at', $currentYear)
            ->orderBy('transactions.created_at', 'DESC')
            ->paginate(5000);

            $totaTransfers = $transactions->filter(function ($transaction) { return $transaction->operation === 'transfert' || $transaction->operation === 'transfert_si'; })->sum('sender_amount');
            $totalReceptions = $transactions->filter(function ($transaction) { return $transaction->operation === 'reception' || $transaction->operation === 'reception_si'; })->sum('sender_amount');

            
            $transactionTransferCount = $transactions->filter(function ($transaction) {return $transaction->operation === 'transfert' || $transaction->operation === 'transfert_si';})->count();
            $transactionReceptionCount = $transactions->filter(function ($transaction) {return $transaction->operation === 'reception' || $transaction->operation === 'reception_si';})->count();

            $cashins = Account::select('cashins.*',
            'users.code as user_code',
            'users.first_name as user_first_name',
            'users.last_name as user_last_name',
            'providers.name as provider_name',
            'providers.phone as provider_phone')
            ->leftJoin('cashins', 'accounts.id', '=', 'cashins.account_id')
            ->leftJoin('users', 'users.id', '=', 'cashins.user_id')
            ->leftJoin('providers', 'providers.id', '=', 'cashins.provider_id')
            ->where('accounts.id', $accountId)
            ->whereMonth('cashins.created_at', $currentMonth)
            ->whereYear('cashins.created_at', $currentYear)
            ->orderBy('cashins.created_at', 'DESC')
            ->paginate(5000); 

            $accountTransferts = Account::select(
                'sender_account.*',
                'users.code as user_code',
                'users.first_name as user_first_name',
                'users.last_name as user_last_name',
                'sender_account.account_sender_name as sender_account_name'
            )
            ->leftJoin('account_transferts as sender_account', 'accounts.id', '=', 'sender_account.account_sender_id')
            ->leftJoin('users', 'users.id', '=', 'sender_account.user_id')
            ->where('accounts.id', $accountId)
            ->whereYear('sender_account.created_at', $currentYear)
            ->whereMonth('sender_account.created_at', $currentMonth)
            ->orderBy('sender_account.created_at', 'DESC')
            ->paginate(5000);

            $receivedTransfers = Account::select(
                'receiver_account.*',
                'users.code as user_code',
                'users.first_name as user_first_name',
                'users.last_name as user_last_name',
                'receiver_account.account_sender_name as receiver_account_name'
            )
            ->leftJoin('account_transferts as receiver_account', 'accounts.id', '=', 'receiver_account.account_receiver_id')
            ->leftJoin('users', 'users.id', '=', 'receiver_account.user_id')
            ->where('accounts.id', $accountId)
            ->whereYear('receiver_account.created_at', $currentYear)
            ->whereMonth('receiver_account.created_at', $currentMonth)
            ->orderBy('receiver_account.created_at', 'DESC')
            ->paginate(5000);

            return response()->json(['transactions' => $transactions , 'totaTransfers' => $totaTransfers,
                                     'totalReceptions' => $totalReceptions, 'transactionTransferCount' => $transactionTransferCount, 
                                     'transactionReceptionCount' => $transactionReceptionCount, 'cashins' => $cashins,
                                     'accountTransferts' => $accountTransferts, 'receivedTransfers' => $receivedTransfers], 200);
        }
    }

    public function getGlobalAccountActivity(Request $request){
        if (Auth::user()->hasRole('super-admin') || Auth::user()->hasRole('admin') || Auth::user()->hasRole('system-engineer') || Auth::user()->hasRole('operator')) {  
            $currentMonth = $request->selected_month;
            $currentYear = $request->selected_year;
            $accountId = $request->account_id;

            $accountActivities = Account::with([
                'transactions' => function ($query) use ($currentYear, $currentMonth) {
                    $query->where('status', 'completed')
                        ->whereYear('completed_date', '=', $currentYear)
                        ->whereMonth('completed_date', '=', $currentMonth)
                        ->orderBy('completed_date', 'ASC');
                },
                'transactions.user',
                'transactions.client',
                'transactions.receiver',
                'transactions.operator',
                'transactions.transaction_activits',
                'cashins' => function ($query) use ($currentYear, $currentMonth) {
                    $query->whereYear('updated_at', '=', $currentYear)
                            ->whereMonth('updated_at', '=', $currentMonth)
                            ->orderBy('updated_at', 'ASC');
                },
                'cashins.provider',
                'cashins.user',
                'cashins.account',
                'cashins.cashin_activits',
                'sentTransfers' => function ($query) use ($currentYear, $currentMonth) {
                    $query->whereYear('updated_at', '=', $currentYear)
                            ->whereMonth('updated_at', '=', $currentMonth)
                            ->orderBy('updated_at', 'ASC');
                },
                'sentTransfers.user',
                'sentTransfers.receiver_account',
                'sentTransfers.sender_account',
                'sentTransfers.account_transfert_activits',
                'receivedTransfers' => function ($query) use ($currentYear, $currentMonth) {
                    $query->whereYear('updated_at', '=', $currentYear)
                            ->whereMonth('updated_at', '=', $currentMonth)
                            ->orderBy('updated_at', 'ASC');
                },
                'receivedTransfers.user',
                'receivedTransfers.receiver_account',
                'receivedTransfers.sender_account',
                'receivedTransfers.account_transfert_activits',
            ])->where('id', $accountId)->first();
            
            $activities = collect([]);

            if ($accountActivities->transactions) {
                $activities = $activities->merge($accountActivities->transactions->map(function ($activity) {
                    $activity['table'] = 'transactions';
                    return $activity;
                }));
            }

            if ($accountActivities->cashins) {
                $activities = $activities->merge($accountActivities->cashins->map(function ($activity) {
                    $activity['table'] = 'cashins';
                    return $activity;
                }));
            }

            if ($accountActivities->sentTransfers) {
                $activities = $activities->merge($accountActivities->sentTransfers->map(function ($activity) {
                    $activity['table'] = 'sent_transfers';
                    return $activity;
                }));
            }

            if ($accountActivities->receivedTransfers) {
                $activities = $activities->merge($accountActivities->receivedTransfers->map(function ($activity) {
                    $activity['table'] = 'received_transfers';
                    return $activity;
                }));
            }

            //Sort activities by date
            $sortedActivities = $activities->sortBy(function ($activity) {
                return $activity['completed_date'] ?? $activity['updated_at'];
            }, SORT_REGULAR, false);

            return response()->json($sortedActivities);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AccountActivit  $accountActivit
     * @return \Illuminate\Http\Response
     */
    public function show(AccountActivit $accountActivit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AccountActivit  $accountActivit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AccountActivit $accountActivit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AccountActivit  $accountActivit
     * @return \Illuminate\Http\Response
     */
    public function destroy(AccountActivit $accountActivit)
    {
        //
    }
}
