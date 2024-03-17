<?php

namespace App\Services\Transactions;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Account;
use App\Models\Transaction;
use App\Rules\ValideAccount;
use App\Models\SystemAccount;
use App\Events\TransactionEvent;
use App\Models\TransactionActivit;
use App\Models\TransactionComment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use App\Models\AgentLoanTransactionRepay;

class TransactionCompletionService
{
    public function completeTransaction($request)
    {
        $laravelTimezone = config('app.timezone');
        Carbon::setTestNow(Carbon::now($laravelTimezone));

        $transaction = Transaction::findOrFail($request->transaction_id);
        $account = Account::findOrFail($request->account_id);
        $user = User::with(['user_account'])->findOrFail($transaction->user_id);
        $operator = User::with(['user_account'])->findOrFail($transaction->operator_id);
        $reference = User::with(['user_account'])->findOrFail($user->reference);
        $systemAccount = SystemAccount::firstOrFail();

        define('AGENT_FEE', (($transaction->fortuna_fee * $user->percentage) / 100));

        define('AGENT_CURRENT_PROFIT', $user->user_account->profits);
        define('AGENT_NEW_PROFIT', AGENT_CURRENT_PROFIT + AGENT_FEE);

        define('AGENT_CURRENT_CAPITAL', $user->user_account->capital);
        define('AGENT_NEW_CAPITAL', $user->user_account->capital + AGENT_FEE);

        define('AGENT_CURRENT_INVESTMENT', $user->user_account->investments);
        define('AGENT_NEW_INVESTMENT',
                $transaction->operation == 'transfert_si' || $transaction->operation == 'transfert' ?
                (AGENT_CURRENT_INVESTMENT - $transaction->sender_amount) : (AGENT_CURRENT_INVESTMENT + $transaction->receiver_amount)
        );

        define('AGENT_CURRENT_CASH_IN_HAND', $user->user_account->cash_in_hand);
        define('AGENT_NEW_CASH_IN_HAND',
                $transaction->operation == 'transfert_si' || $transaction->operation == 'transfert' ?
                (AGENT_CURRENT_CASH_IN_HAND + $transaction->sender_amount) : (AGENT_CURRENT_CASH_IN_HAND - $transaction->receiver_amount)
        );

        //Calculate Operator fee
        define('OPERATOR_FEE', 10);
        define('OPERATOR_CURRENT_PROFIT', $operator->user_account->profits);
        define('OPERATOR_NEW_PROFIT', (OPERATOR_CURRENT_PROFIT + OPERATOR_FEE));

        //Calculate Reference fee
        define('REFERENCE_FEE', (($transaction->fortuna_fee - AGENT_FEE) * 10) / 100);
        define('REFERENCE_CURRENT_PROFIT', $reference->hasRole('super-admin') ? 0.0 : $reference->user_account->referral_commissions);
        define('REFERENCE_NEW_PROFIT', $reference->hasRole('super-admin') ? 0.0 : REFERENCE_CURRENT_PROFIT + REFERENCE_FEE);

        define('REFERENCE_CURRENT_CAPITAL', $reference->hasRole('super-admin') ? 0.0 : $reference->user_account->capital);
        define('REFERENCE_NEW_CAPITAL', $reference->hasRole('super-admin') ? 0.0 : REFERENCE_CURRENT_CAPITAL + REFERENCE_FEE);

        define('ACCOUNT_CURRENT_BALANCE', $account->balance);
        define('ACCOUNT_NEW_BALANCE', $transaction->operation == 'transfert_si' || $transaction->operation == 'transfert' ? (ACCOUNT_CURRENT_BALANCE - $request->amount) : (ACCOUNT_CURRENT_BALANCE + ($transaction->sender_amount - $transaction->p_to_p_fee)));

        //Calculate fortuna fee
        define('FORTUNA_FEE_MINUS_USER_FEE', ($transaction->fortuna_fee - AGENT_FEE));
        define('FORTUNA_FEE_MINUS_AGENT_AND_OPERATOR_FEE', FORTUNA_FEE_MINUS_USER_FEE - OPERATOR_FEE);
        

        $accountUpdateResult = null;
        $referralCommissionUpdateResult = null;
        $transactionUpdateResult = null; 
        $userAccountUpdateResult = null; 
        $transactionHistoryUpdateResult = null;
        $accountMonthlyCountUpdateResult = null;
        $operatorAccountUpdateResult = null;

        if ($transaction->status === 'approved') {

            $incrementValue = 1;
            // Account transaction count limit (Retrieve the first entry of the current month)
            $now = now();
            $firstEntry = Transaction::where('account_id', $request->account_id)->whereMonth('created_at', $now->format('m'))->whereYear('created_at', $now->format('Y'))->orderBy('created_at')->first();
           
            if ($firstEntry) {
                if ($account->total_monthly_transactions < 50) {
                    if ($transaction->operation == 'transfert_si' || $transaction->operation == 'transfert') {
                        $incrementValue += $account->total_monthly_transactions;
                        //$account->update(['total_monthly_transactions' => ($account->total_monthly_transactions + 1)]);
                    }
                } else {
                    if ($account->type == 'natcash' ||  $account->category == 'business') {
                        if ($transaction->operation == 'transfert_si' || $transaction->operation == 'transfert') {
                            $incrementValue += $account->total_monthly_transactions;
                           // $account->update(['total_monthly_transactions' => ($account->total_monthly_transactions + 1)]);
                        }
                    } else {
                        return response()->json([
                            'message' => 'Account limit reached',
                            'errors' => [
                                'limit_reached' => [
                                    'Account Transactions limit reached! please select an other account.'
                                ]
                            ]
                        ], 400);
                    }
                }
            } else {
                $incrementValue = 1;
                //$account->update(['total_monthly_transactions' => 1]);
            }
            // Account transaction count limit end


            if ($transaction->operation == 'transfert_si' || $transaction->operation == 'reception_si' || $transaction->operation == 'reception') {
                switch ($transaction->operation) {
                    case 'transfert_si':
                        switch (true) {
                            case $transaction->sender_amount <= $user->user_account->investments:
                                info('Transaction(Transfert) success');
                                break;
                            case $transaction->sender_amount > $user->user_account->investments:
                                return response()->json([
                                    'message' => 'Agent deposit required!',
                                    'errors' => [
                                        'debt_not_allowed' => [
                                            'Please report that case to a system administrator.'
                                        ]
                                    ]
                                ], 400);
                                break;
                            default:
                                return response()->json([
                                    'message' => 'Error occurred',
                                    'errors' => [
                                        'transfert_si_undefined_issue' => [
                                            'An error occurred during transaction completion please try again. if the issue persist contact a system administrator!'
                                        ]
                                    ]
                                ], 400);
                                break;
                        }
                        break;
                    case 'reception_si':
                    case 'reception':
                        info('Transaction(Reception) success');
                        break;

                    default:
                        return response()->json([
                            'message' => 'Error occured please contact system administror!',
                            'errors' => [
                                'debt_not_allowed' => [
                                    'Transaction blocked!'
                                ]
                            ]
                        ], 400);
                        break;
                }
                // ----End user calcule-------

                try {
                    DB::beginTransaction();
                    // Account check
                    switch ($transaction->operation) {
                        case 'transfert_si':
                        case 'transfert':
                            if (ACCOUNT_CURRENT_BALANCE >= $request->amount) {
                                $request->validate(['amount' => new ValideAccount(ACCOUNT_CURRENT_BALANCE)]);
                                $accountUpdateResult = $account->update(['balance' => ACCOUNT_NEW_BALANCE]);
                            }else{
                                return response()->json([
                                    'message' => 'Insufficient account balance!',
                                    'errors' => [
                                        'insufficient_account_balance' => [
                                            'Insufficient account balance!'
                                        ]
                                    ]
                                ], 400);
                            }
                            break;
                        case 'reception_si':
                        case 'reception':
                            $accountUpdateResult = $account->update(['balance' => ACCOUNT_NEW_BALANCE]);
                            break;
                        default:
                            //
                            break;
                    }
                    // End account check  

                     // Comment section
                     if ($request->comment) {
                        $comment = new TransactionComment;
                        $comment->category = $request->comment_category;
                        $comment->content = $request->comment;
                        $comment->user_id = Auth::id();
                        $comment->transaction_id = $request->transaction_id;
                        $comment->timing = $request->status;
                        $comment->save();
                    }
                    // End comment Section

                    // Update transaction info
                    $transactionUpdateResult = $transaction->update([
                        'status' => $request->status,
                        'completed_by' => Auth::id(),
                        'completed_date' => Carbon::now($laravelTimezone),
                        'account_id' => $request->account_id,
                    ]);
                    // End Update transaction info

                    // $systemAccount = SystemAccount::findOrFail(1);

                    // Update system account (revenue) and reference profits
                    if ($reference->hasRole('super-admin')) {
                        $systemRevenue = FORTUNA_FEE_MINUS_AGENT_AND_OPERATOR_FEE;
                        $referralCommissionUpdateResult = $systemAccount->update(['user_payroll' => $systemAccount->user_payroll + (AGENT_NEW_PROFIT + OPERATOR_NEW_PROFIT)]);
                    } else {
                        $referralCommissionUpdateResult = $reference->user_account->update(['referral_commissions' => REFERENCE_NEW_PROFIT]);
                        if ($reference->hasRole('agent')) {
                            $reference->user_account->update(['capital' => REFERENCE_NEW_CAPITAL]);
                        }
                        $systemRevenue = FORTUNA_FEE_MINUS_AGENT_AND_OPERATOR_FEE - REFERENCE_FEE;
                        $systemAccount->update(['user_payroll' => $systemAccount->user_payroll + (AGENT_NEW_PROFIT + REFERENCE_NEW_PROFIT + OPERATOR_NEW_PROFIT)]);
                    }                    
                        
                    $systemAccount->update(['revenues' => $systemAccount->revenues + $systemRevenue]);

                    // Update Agent Profit and investment
                    $userAccountUpdateResult = $user->user_account->update([
                        'profits' => AGENT_NEW_PROFIT,
                        'investments' => AGENT_NEW_INVESTMENT,
                        'cash_in_hand' => AGENT_NEW_CASH_IN_HAND,
                        'capital' => AGENT_NEW_CAPITAL,
                    ]);

                    // Update Operator profits by 10 Pesos
                    $operatorAccountUpdateResult = $operator->user_account->update(['profits' => OPERATOR_NEW_PROFIT]);
             
                    //End check user role in other to calculate the fees

                    // Set Transaction History
                    $transactionHistoryUpdateResult = $transaction_history = new TransactionActivit;
                    $transaction_history->sender_amount =  $transaction->sender_amount;
                    $transaction_history->receiver_amount = $transaction->receiver_amount;
                    $transaction_history->qr_code = $transaction->qr_code;
                    $transaction_history->rate = $transaction->rate;
                    $transaction_history->fortuna_fee = $transaction->fortuna_fee;
                    $transaction_history->p_to_p_fee = $transaction->p_to_p_fee;
                    $transaction_history->status = $request->status;
                    $transaction_history->type = $transaction->type;
                    $transaction_history->operation = $transaction->operation;
                    $transaction_history->sender =  $transaction->sender;
                    $transaction_history->receiver = $transaction->receiver;
                    $transaction_history->user_id = $transaction->user_id;
                    $transaction_history->client_id = $transaction->client_id;
                    $transaction_history->operator_id = $transaction->operator_id;
                    $transaction_history->transaction_id = $transaction->id;
                    $transaction_history->account_id = $transaction->account_id;
                    $transaction_history->approved_by = $transaction->approved_by;
                    $transaction_history->completed_by = $transaction->completed_by;
                    $transaction_history->approved_date = $transaction->approved_date;
                    $transaction_history->completed_date = $transaction->completed_date;

                    $transaction_history->current_agent_balance = AGENT_CURRENT_PROFIT;
                    $transaction_history->new_agent_balance = AGENT_NEW_PROFIT;

                    $transaction_history->current_agent_investment = AGENT_CURRENT_INVESTMENT;
                    $transaction_history->new_agent_investment = AGENT_NEW_INVESTMENT;

                    $transaction_history->current_operator_balance = OPERATOR_CURRENT_PROFIT;
                    $transaction_history->new_operator_balance = OPERATOR_NEW_PROFIT;

                    $transaction_history->current_reference_balance = REFERENCE_CURRENT_PROFIT;
                    $transaction_history->new_reference_balance = REFERENCE_NEW_PROFIT;

                    $transaction_history->current_account_balance = ACCOUNT_CURRENT_BALANCE;
                    $transaction_history->new_account_balance = ACCOUNT_NEW_BALANCE;

                    $transaction_history->current_agent_cash_in_hand = $transaction->operation == 'transfert_si' || $transaction->operation == 'transfert' ?
                                                                        (AGENT_CURRENT_CASH_IN_HAND - $transaction->sender_amount) : (AGENT_CURRENT_CASH_IN_HAND + $transaction->receiver_amount);
                    $transaction_history->new_agent_cash_in_hand = AGENT_NEW_CASH_IN_HAND;

                    $transaction_history->current_agent_capital = AGENT_NEW_CAPITAL - AGENT_FEE;
                    $transaction_history->new_agent_capital = AGENT_NEW_CAPITAL;

                    // $transaction_history->current_system_fund = 0;
                    // $transaction_history->new_system_fund = 0;
                    $transaction_history->step = 'completed';
                    $transaction_history->save();
                    // Transaction History end

                    // update account total_monthly_transactions
                    $accountMonthlyCountUpdateResult = $account->update(['total_monthly_transactions' => $incrementValue]);
                    // update account total_monthly_transactions end

                    if ($accountUpdateResult && $transactionUpdateResult && $referralCommissionUpdateResult && $userAccountUpdateResult && $operatorAccountUpdateResult && $transactionHistoryUpdateResult && $accountMonthlyCountUpdateResult) {
                        DB::commit();

                        event(new TransactionEvent($transaction, $user));

                        //Debt payment
                            try{
                                DB::beginTransaction();
                                // Agent debt payment
                                if ($user->user_account->depts > 0) {

                                    $AGENT_CURRENT_DEBT = $user->user_account->depts;
                                    $AGENT_NEW_DEBT = null;

                                    $AGENT_CURRENT_COMMISSION = $user->user_account->profits;
                                    $AGENT_NEW_COMMISSION = $user->user_account->profits;

                                    $REGULAR_AGENT_FEE = (($transaction->fortuna_fee * $user->percentage) / 100);
                                    $AGENT_FEE = null;
                                    $PAID_AMOUNT = null;

                                    $AGENT_FEE_IN_DEBT_CASE = (($REGULAR_AGENT_FEE * $user->loan_percentage) / 100);

                                    $AGENT_FEE_HIGHER_THEN_DEBT = ($AGENT_FEE_IN_DEBT_CASE + ($AGENT_FEE_IN_DEBT_CASE - $AGENT_CURRENT_DEBT));

                                        if ($AGENT_CURRENT_DEBT >= $AGENT_FEE_IN_DEBT_CASE) {
                                            $AGENT_FEE = $AGENT_FEE_IN_DEBT_CASE;
                                            $PAID_AMOUNT = $REGULAR_AGENT_FEE - $AGENT_FEE_IN_DEBT_CASE;
                                            $AGENT_NEW_DEBT = $AGENT_CURRENT_DEBT - $PAID_AMOUNT;
                                        } else {
                                            $AGENT_FEE = $AGENT_FEE_HIGHER_THEN_DEBT;
                                            $PAID_AMOUNT = $REGULAR_AGENT_FEE - $AGENT_FEE_HIGHER_THEN_DEBT;
                                            $AGENT_NEW_DEBT = 0;
                                        }

                                    $AGENT_NEW_COMMISSION = $AGENT_CURRENT_COMMISSION - $PAID_AMOUNT;

                                    // Agent loan payment
                                    $payment = new AgentLoanTransactionRepay;
                                    $payment->amount = $PAID_AMOUNT;
                                    $payment->transaction_commission = $AGENT_FEE;
                                    $payment->user_id = $transaction->user_id;
                                    $payment->transaction_id = $transaction->id;
                                    $payment->loan_percentage = $user->loan_percentage;
                                    $payment->completed_date = Carbon::now($laravelTimezone);

                                    $payment->current_agent_commission = $AGENT_CURRENT_COMMISSION;
                                    $payment->new_agent_commission = $AGENT_NEW_COMMISSION;
                                    $payment->current_agent_debt = $AGENT_CURRENT_DEBT;
                                    $payment->new_agent_debt = $AGENT_NEW_DEBT;

                                    $payment->current_system_claim = $systemAccount->claim;
                                    $payment->new_system_claim = $systemAccount->claim;

                                    $payment->current_system_fund = $systemAccount->fund;
                                    $payment->new_system_fund = $systemAccount->fund;
                                    $payment->save();

                                    // $transac_history = TransactionActivit::findOrFail($transaction_history->id);
                                    // $transac_history->update(['current_agent_balance' => $AGENT_CURRENT_COMMISSION,
                                    //                         'new_agent_balance' => $AGENT_NEW_COMMISSION]);
                                    // $transac_history->save();
                                    // End Agent loan payment

                                    $userAccountUpdateResult = $user->user_account->update([
                                        'profits' => $AGENT_NEW_COMMISSION,
                                        'depts' => $AGENT_NEW_DEBT,
                                    ]);
                                }
                                // End agent debt payment
                                DB::commit();
                            } catch (QueryException $exception) {
                                DB::rollBack();
                                Log::error('Error durring transaction completion: ' . $exception->getMessage());
                                return response()->json([
                                    'message' => 'Error occured durring transaction completion!',
                                    'errors' => [
                                        'throw_execetion' => [
                                            'Error occured durring debt payment.'
                                        ]
                                    ]
                                ], 400);
                                //throw new \Exception('Error occured durring transaction completion' . $exception->getMessage());
                            }
                        // End debt payment   
                        return Transaction::with(['client'])->orderBy('created_at', 'DESC')->paginate(50);
                    }else {
                        DB::rollBack();
                        return response()->json([
                            'message' => 'Error occured durring transaction completion!',
                            'errors' => [
                                'throw_execetion' => [
                                    'Error occured durring transaction completion! if the issue persist please contact system administrator..'
                                ]
                            ]
                        ], 400);
                    }
                   
                } catch (QueryException $exception) {
                    DB::rollBack();
                    Log::error('Error durring transaction completion: ' . $exception->getMessage());
                    return response()->json([
                        'message' => 'Error occured durring transaction completion!',
                        'errors' => [
                            'throw_execetion' => [
                                'Error occured durring transaction completion! if the issue persist please contact system administrator.'
                            ]
                        ]
                    ], 400);
                    //throw new \Exception('Error occured durring transaction completion' . $exception->getMessage());
                }
            } //
            else {
                return response()->json([
                    'message' => 'The given data was invalid.',
                    'errors' => [
                        'completed_transaction' => [
                            'This transaction has already been completed or an other operator is already on it!'
                        ]
                    ]
                ], 400);
            }
        }
        // 
    }
}