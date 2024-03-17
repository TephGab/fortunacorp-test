<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Expense;
use Illuminate\Http\Request;
use App\Models\SystemAccount;
use App\Http\Controllers\Controller;
use App\Models\ExpenseType;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Expense::orderBy('created_at', 'DESC')->paginate(50);
       // return Expense::with('users')->orderBy('created_at', 'DESC')->paginate(50);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required',
            'currency' => 'required',
            'deduct_from' => 'required',
            'type' => 'required',
        ]);

        $system_account = SystemAccount::first();
        $laravelTimezone = config('app.timezone');
        Carbon::setTestNow(Carbon::now($laravelTimezone));

        switch (true) {
            case $request->deduct_from == 'system_funds':
                if ($request->amount <= $system_account->funds) {
                    info('Passed');
                }else{
                    return response()->json([
                        'message' => 'Insufficient system funds!',
                        'errors' => [
                            'insufficient_system_funds' => [
                                'Insufficient system funds!'
                            ]
                        ]
                    ], 400);
                }
            break;
            case $request->deduct_from == 'system_revenues':
                if ($request->amount <= $system_account->revenues) {
                    info('Passed');
                }else{
                    return response()->json([
                        'message' => 'Insufficient system revenues!',
                        'errors' => [
                            'insufficient_system_funds' => [
                                'Insufficient system revenues!'
                            ]
                        ]
                    ], 400);
                }
            break;
            case $request->deduct_from == 'personal_funds':
                info('Passed');
            break;
            default:
            //
                break;
        }

        $expense = new Expense;
        $expense->amount = $request->amount;
        $expense->currency = $request->currency;
        $expense->deduct_from = $request->deduct_from;
        $expense->type = $request->type;
        $expense->comment = $request->comment;
        $expense->user_id = Auth::id();
        if ($request->deduct_from == 'system_funds') {
            $expense->current_funds = $system_account->funds;
            $expense->new_funds = ($system_account->funds - $request->amount);
            $expense->current_revenues = $system_account->revenues;
            $expense->new_revenues = $system_account->revenues;
            $system_account->update(['funds' => $system_account->funds - $request->amount]);
        }
        elseif ($request->deduct_from == 'system_revenues') {
            $expense->current_revenues = $system_account->revenues;
            $expense->new_revenues = ($system_account->revenues - $request->amount);
            $expense->current_funds = $system_account->funds;
            $expense->new_funds = $system_account->funds;
            $system_account->update(['revenues' => $system_account->revenues - $request->amount]);
        }elseif ($request->deduct_from == 'personal_funds') {
            $expense->current_revenues = $system_account->revenues;
            $expense->new_revenues = $system_account->revenues;
            $expense->current_funds = $system_account->funds;
            $expense->new_funds = $system_account->funds;
        }
        $expense->expense_date = Carbon::now($laravelTimezone);
        $expense->save();

        $expensetype = new ExpenseType;
        $expensetype->code = '00'.$expense->id;
        $expensetype->category = $request->type;
        $expensetype->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function show(Expense $expense)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Expense $expense)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function destroy(Expense $expense)
    {
        //
    }
}
