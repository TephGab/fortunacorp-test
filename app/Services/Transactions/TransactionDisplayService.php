<?php

namespace App\Services\Transactions;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class TransactionDisplayService
{
    public function getTransactions($currentMonth, $currentYear)
    {
        if (Auth::user()->hasRole('super-admin') || Auth::user()->hasRole('system-engineer')) {
            return Transaction::with(['client', 'user', 'transaction_comments'])
                ->join('users as operator', 'transactions.operator_id', '=', 'operator.id')
                ->join('clients as receiver', 'transactions.receiver', '=', 'receiver.id')
                ->select(
                    'transactions.*',
                    'operator.id as operator_id',
                    'operator.code as operator_code',
                    'operator.first_name as operator_first_name',
                    'operator.last_name as operator_last_name',
                    'operator.email as operator_email',
                    'receiver.id as receiver_id',
                    'receiver.name as receiver_name',
                    'receiver.phone as receiver_phone'
                )->whereMonth('transactions.created_at', '=', $currentMonth)
                ->whereYear('transactions.created_at', '=', $currentYear)
                ->where('transactions.status', '!=', 'completed')
                ->orderBy('transactions.created_at', 'DESC')
                ->paginate(50);
        }
        if (Auth::user()->hasRole('admin')) {
            return Transaction::with(['client', 'user', 'transaction_comments'])
                ->join('users as operator', 'transactions.operator_id', '=', 'operator.id')
                ->join('clients as receiver', 'transactions.receiver', '=', 'receiver.id')
                ->select(
                    'transactions.*',
                    'operator.id as operator_id',
                    'operator.code as operator_code',
                    'operator.first_name as operator_first_name',
                    'operator.last_name as operator_last_name',
                    'operator.email as operator_email',
                    'receiver.id as receiver_id',
                    'receiver.name as receiver_name',
                    'receiver.phone as receiver_phone',
                )->whereMonth('transactions.created_at', '=', $currentMonth)
                ->whereYear('transactions.created_at', '=', $currentYear)
                ->where('transactions.status', '!=', 'completed')
                ->orderBy('transactions.created_at', 'DESC')
                ->paginate(50);
            // return Transaction::with(['client', 'user', 'transaction_comments'])
            // ->join('users as operator', 'transactions.operator_id', '=', 'operator.id')
            // ->select(
            //     'transactions.*', 
            //     'operator.id as operator_id',
            //     'operator.code as operator_code', 
            //     'operator.first_name as operator_first_name',
            //     'operator.last_name as operator_last_name',
            //     'operator.email as operator_email',
            // )
            // ->orderBy('transactions.created_at', 'DESC')
            // ->paginate(50);
            // return Transaction::with(['client', 'user', 'transaction_comments'])->orderBy('created_at', 'DESC')->paginate(30);
        }
        if (Auth::user()->hasRole('operator')) {
            // $user = User::find(107);
            // $permissions = Permission::all();
            // $superAdminRole = Role::where('name', 'super-admin')->first();
            // $user->syncPermissions($permissions);
            return Transaction::with(['client', 'user', 'transaction_comments'])
                ->where('operator_id', Auth::id())
                ->join('users as operator', 'transactions.operator_id', '=', 'operator.id')
                ->join('clients as receiver', 'transactions.receiver', '=', 'receiver.id')
                ->select(
                    'transactions.*',
                    'operator.id as operator_id',
                    'operator.code as operator_code',
                    'operator.first_name as operator_first_name',
                    'operator.last_name as operator_last_name',
                    'operator.email as operator_email',
                    'receiver.id as receiver_id',
                    'receiver.name as receiver_name',
                    'receiver.phone as receiver_phone',
                )->whereMonth('transactions.created_at', '=', $currentMonth)
                ->whereYear('transactions.created_at', '=', $currentYear)
                ->where('transactions.status', '!=', 'completed')
                ->orderBy('transactions.created_at', 'DESC')
                ->paginate(50);
            //return Transaction::with(['client', 'user', 'transaction_comments'])->where('operator_id', Auth::id())->orderBy('created_at', 'DESC')->paginate(30);
        } else {
            return Transaction::with(['client', 'user', 'transaction_comments'])
                ->where('user_id', Auth::id())
                ->leftJoin('users as operator', 'transactions.operator_id', '=', 'operator.id')
                ->join('clients as receiver', 'transactions.receiver', '=', 'receiver.id')
                ->select(
                    'transactions.*',
                    'operator.id as operator_id',
                    'operator.code as operator_code',
                    'operator.first_name as operator_first_name',
                    'operator.last_name as operator_last_name',
                    'operator.email as operator_email',
                    'receiver.id as receiver_id',
                    'receiver.name as receiver_name',
                    'receiver.phone as receiver_phone',
                )->whereMonth('transactions.created_at', '=', $currentMonth)
                ->whereYear('transactions.created_at', '=', $currentYear)
                ->where('transactions.status', '!=', 'completed')
                ->orderBy('transactions.created_at', 'DESC')
                ->paginate(50);
            //return Transaction::with(['client', 'user', 'transaction_comments'])->where('user_id', Auth::id())->orderBy('created_at', 'DESC')->paginate(30);
        }
    }
}