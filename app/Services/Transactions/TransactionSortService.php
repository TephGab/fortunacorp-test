<?php

namespace App\Services\Transactions;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class TransactionSortService
{
    public function sortTransaction($request, $currentMonth, $currentYear)
    {
        if (Auth::user()->hasRole('super-admin') || Auth::user()->hasRole('system-engineer') || Auth::user()->hasRole('admin')) {
            if ($request->type == 'all') {
                if ($request->solde_sort == 'max_solde') {
                    if ($request->status == 'all') {
                        return Transaction::with(['client'])->orderBy('receiver_amount', 'DESC')->paginate(200);
                    }
                    if ($request->status == 'pending' || $request->status == 'approved' || $request->status == 'disapproved' || $request->status == 'completed') {
                        return Transaction::with(['client'])->where('status', $request->status)->orderBy('receiver_amount', 'DESC')->paginate(200);
                    }
                }
                if ($request->solde_sort == 'min_solde') {
                    if ($request->status == 'all') {
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
                        ->orderBy('transactions.receiver_amount', 'ASC')
                        ->paginate(50);
                       // return Transaction::with(['client'])->orderBy('receiver_amount', 'ASC')->paginate(200);
                    }
                    if ($request->status == 'pending' || $request->status == 'approved' || $request->status == 'disapproved' || $request->status == 'completed') {
                        return Transaction::with(['client'])->where('status', $request->status)->orderBy('receiver_amount', 'ASC')->paginate(200);
                    }
                }
            }
            if ($request->type == 'moncash' || $request->type == 'natcash') {
                if ($request->solde_sort == 'max_solde') {
                    if ($request->status == 'all') {
                        return Transaction::with(['client'])->where('type', $request->type)->orderBy('receiver_amount', 'DESC')->paginate(200);
                    }
                    if ($request->status == 'pending' || $request->status == 'approved' || $request->status == 'disapproved' || $request->status == 'completed') {
                        return Transaction::with(['client'])->where('type', $request->type)->where('status', $request->status)->orderBy('receiver_amount', 'DESC')->paginate(200);
                    }
                }
                if ($request->solde_sort == 'min_solde') {
                    if ($request->status == 'all') {
                        return Transaction::with(['client'])->where('type', $request->type)->orderBy('receiver_amount', 'ASC')->paginate(200);
                    }
                    if ($request->status == 'pending' || $request->status == 'approved' || $request->status == 'disapproved' || $request->status == 'completed') {
                        return Transaction::with(['client'])->where('type', $request->type)->where('status', $request->status)->orderBy('receiver_amount', 'ASC')->paginate(200);
                    }
                }
            }
        }
        if (Auth::user()->hasRole('operator')) {
            if ($request->type == 'all') {
                if ($request->solde_sort == 'max_solde') {
                    if ($request->status == 'all') {
                        return Transaction::with(['client'])->where('operator_id', Auth::id())->orderBy('receiver_amount', 'DESC')->paginate(200);
                    }
                    if ($request->status == 'pending' || $request->status == 'approved' || $request->status == 'disapproved' || $request->status == 'completed') {
                        return Transaction::with(['client'])->where('status', $request->status)->where('operator_id', Auth::id())->orderBy('receiver_amount', 'DESC')->paginate(200);
                    }
                }
                if ($request->solde_sort == 'min_solde') {
                    if ($request->status == 'all') {
                        return Transaction::with(['client'])->where('operator_id', Auth::id())->orderBy('receiver_amount', 'ASC')->paginate(200);
                    }
                    if ($request->status == 'pending' || $request->status == 'approved' || $request->status == 'disapproved' || $request->status == 'completed') {
                        return Transaction::with(['client'])->where('status', $request->status)->where('operator_id', Auth::id())->orderBy('receiver_amount', 'ASC')->paginate(200);
                    }
                }
            }
            if ($request->type == 'moncash' || $request->type == 'natcash') {
                if ($request->solde_sort == 'max_solde') {
                    if ($request->status == 'all') {
                        return Transaction::with(['client'])->where('type', $request->type)->where('operator_id', Auth::id())->orderBy('receiver_amount', 'DESC')->paginate(200);
                    }
                    if ($request->status == 'pending' || $request->status == 'approved' || $request->status == 'disapproved' || $request->status == 'completed') {
                        return Transaction::with(['client'])->where('type', $request->type)->where('status', $request->status)->where('operator_id', Auth::id())->orderBy('receiver_amount', 'DESC')->paginate(200);
                    }
                }
                if ($request->solde_sort == 'min_solde') {
                    if ($request->status == 'all') {
                        return Transaction::with(['client'])->where('type', $request->type)->where('operator_id', Auth::id())->orderBy('receiver_amount', 'ASC')->paginate(200);
                    }
                    if ($request->status == 'pending' || $request->status == 'approved' || $request->status == 'disapproved' || $request->status == 'completed') {
                        return Transaction::with(['client'])->where('type', $request->type)->where('status', $request->status)->where('operator_id', Auth::id())->orderBy('receiver_amount', 'ASC')->paginate(200);
                    }
                }
            }
        } else {
            if ($request->type == 'all') {
                if ($request->solde_sort == 'max_solde') {
                    if ($request->status == 'all') {
                        return Transaction::with(['client'])->where('user_id', Auth::id())->orderBy('receiver_amount', 'DESC')->paginate(200);
                    }
                    if ($request->status == 'pending' || $request->status == 'approved' || $request->status == 'disapproved' || $request->status == 'completed') {
                        return Transaction::with(['client'])->where('status', $request->status)->where('user_id', Auth::id())->orderBy('receiver_amount', 'DESC')->paginate(200);
                    }
                }
                if ($request->solde_sort == 'min_solde') {
                    if ($request->status == 'all') {
                        return Transaction::with(['client'])->where('user_id', Auth::id())->orderBy('receiver_amount', 'ASC')->paginate(200);
                    }
                    if ($request->status == 'pending' || $request->status == 'approved' || $request->status == 'disapproved' || $request->status == 'completed') {
                        return Transaction::with(['client'])->where('status', $request->status)->where('user_id', Auth::id())->orderBy('receiver_amount', 'ASC')->paginate(200);
                    }
                }
            }
            if ($request->type == 'moncash' || $request->type == 'natcash') {
                if ($request->solde_sort == 'max_solde') {
                    if ($request->status == 'all') {
                        return Transaction::with(['client'])->where('type', $request->type)->where('user_id', Auth::id())->orderBy('receiver_amount', 'DESC')->paginate(200);
                    }
                    if ($request->status == 'pending' || $request->status == 'approved' || $request->status == 'disapproved' || $request->status == 'completed') {
                        return Transaction::with(['client'])->where('type', $request->type)->where('status', $request->status)->where('user_id', Auth::id())->orderBy('receiver_amount', 'DESC')->paginate(200);
                    }
                }
                if ($request->solde_sort == 'min_solde') {
                    if ($request->status == 'all') {
                        return Transaction::with(['client'])->where('type', $request->type)->where('user_id', Auth::id())->orderBy('receiver_amount', 'ASC')->paginate(200);
                    }
                    if ($request->status == 'pending' || $request->status == 'approved' || $request->status == 'disapproved' || $request->status == 'completed') {
                        return Transaction::with(['client'])->where('type', $request->type)->where('status', $request->status)->where('user_id', Auth::id())->orderBy('receiver_amount', 'ASC')->paginate(200);
                    }
                }
            }
        }
    }
}