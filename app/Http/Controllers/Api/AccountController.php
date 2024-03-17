<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Account;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->hasRole('super-admin') || Auth::user()->hasRole('admin') || Auth::user()->hasRole('system-engineer')) {
            return Account::with(['user'])->orderBy('created_at', 'DESC')->paginate(50);
        }
        if (Auth::user()->hasRole('operator')) {
            return Account::where('user_id', Auth::id())->with(['user'])->orderBy('created_at', 'DESC')->paginate(50);
        }
    }

    public function assignAccountToOperator(Request $request){
        $account = Account::find($request->account_id);
        $user = User::find($request->user_id);

        $account->operators()->attach($user);
    }

    public function getOperatorAccounts(Request $request){
        if ($request->operator !== 'all') {
            $operator_acc = User::with('operator_accounts')->find($request->operator);
            $all_accounts = Account::all();
            return response()->json(['operator_accs' => $operator_acc->operator_accounts, 'all_accounts' => $all_accounts], 200);
        }
    }

    public function updateOperatorAccounts(Request $request)
    {
        $operatorId = $request->input('operator');
        $selectedAccounts = $request->input('selectedAccounts');

        // Find the user based on the operator ID
        $operator = User::with('operator_accounts')->find($operatorId);

        if (!$operator) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Update operator accounts for the user
        $operator->operator_accounts()->sync($selectedAccounts);

        return response()->json(['message' => 'Operator accounts updated successfully']);
    }

    public function searchByNumber(Request $request)
    { 
        if (Auth::user()->hasRole('super-admin') || Auth::user()->hasRole('admin') || Auth::user()->hasRole('system-engineer')) {
            if ($request->input('number') !== null) {
                // Sanitize and validate the input
                $phoneNumber = str_replace(' ', '', $request->input('number'));
            
                // Perform the query with TRIM
                $accounts = Account::with(['user'])
                    ->whereRaw("REPLACE(phone, ' ', '') LIKE '%" . $phoneNumber . "%'")
                    ->orderBy('created_at', 'DESC')
                    ->paginate(50);
            
                return $accounts;
            } else {
                // Handle the case when 'number' is null
                return response()->json(['error' => 'Number parameter is missing.'], 400);
            }
        }
        if(Auth::user()->hasRole('operator')){
            if ($request->input('number') !== null) {
                // Sanitize and validate the input
                $phoneNumber = str_replace(' ', '', $request->input('number'));
            
                // Perform the query with TRIM
                $accounts = Account::with(['user'])
                    ->where('user_id', Auth::id())
                    ->whereRaw("REPLACE(phone, ' ', '') LIKE '%" . $phoneNumber . "%'")
                    ->orderBy('created_at', 'DESC')
                    ->paginate(50);
            
                return $accounts;
            } else {
                // Handle the case when 'number' is null
                return response()->json(['error' => 'Number parameter is missing.'], 400);
            }
        }
        
    }

    public function searchByName(Request $request)
    {
        if (Auth::user()->hasRole('super-admin') || Auth::user()->hasRole('admin') || Auth::user()->hasRole('system-engineer')) {
            if ($request->input('name') !== null) {
                // Sanitize and validate the input
                $accountName = str_replace(' ', '', $request->input('name'));
            
                // Perform the query with TRIM
                $accounts = Account::with(['user'])
                    ->whereRaw("REPLACE(name, ' ', '') LIKE '%" . $accountName . "%'")
                    ->orderBy('created_at', 'DESC')
                    ->paginate(50);
            
                return $accounts;
            } else {
                // Handle the case when 'number' is null
                return response()->json(['error' => 'Number parameter is missing.'], 400);
            }
        }
        if(Auth::user()->hasRole('operator')){
            if ($request->input('name') !== null) {
                // Sanitize and validate the input
                $accountName = str_replace(' ', '', $request->input('name'));
            
                // Perform the query with TRIM
                $accounts = Account::with(['user'])
                    ->where('user_id', Auth::id())
                    ->whereRaw("REPLACE(name, ' ', '') LIKE '%" . $accountName . "%'")
                    ->orderBy('created_at', 'DESC')
                    ->paginate(50);
            
                return $accounts;
            } else {
                // Handle the case when 'number' is null
                return response()->json(['error' => 'Number parameter is missing.'], 400);
            }
        }
    }

    public function getAccountTotalMonthlyTransactions(Request $request){
       return Account::where('id', $request->id)->first('total_monthly_transactions');
        // return response()->json(['total_monthly_transactions' => $total_monthly_transactions], 200);
    }

    public function accountSum(){
        if (Auth::user()->hasRole('super-admin') || Auth::user()->hasRole('admin') || Auth::user()->hasRole('system-engineer')) {
            $total_moncash = Account::where('type', 'moncash')->sum('balance');
            $total_natcash = Account::where('type', 'natcash')->sum('balance');
    
            return response()->json(['moncash' => $total_moncash, 'natcash' => $total_natcash], 200);
        }
        if(Auth::user()->hasRole('operator')){
            $total_moncash = Account::where('type', 'moncash')->where('user_id', Auth::id())->sum('balance');
            $total_natcash = Account::where('type', 'natcash')->where('user_id', Auth::id())->sum('balance');
    
            return response()->json(['moncash' => $total_moncash, 'natcash' => $total_natcash], 200);
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
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'type' => 'required',
            'category' => 'required',
            'operator_id' => 'required',
        ]);

        $account = new Account;
        $account->name = $request->name;
        $account->phone = $request->phone;
        $account->full_wallet = $request->full_wallet;
        $account->balance = $request->balance;
        $account->category = $request->category;
        $account->type = $request->type;
        $account->user_id = $request->operator_id;
        $account->save();
    }

    public function accountSort(Request $request)
    {
        if (Auth::user()->hasRole('super-admin') || Auth::user()->hasRole('admin') || Auth::user()->hasRole('system-engineer')) {
            $query = Account::with('user');

            // Sorting conditions
            if ($request->type !== 'all') {
                $query->where('type', $request->type);
            }

            if ($request->operator !== 'all') {
                $query->where('user_id', $request->operator);
            }

            if ($request->solde_sort !== 'all') {
                if ($request->solde_sort === 'max_solde') {
                    $query->orderBy('balance', 'DESC');
                } elseif ($request->solde_sort === 'min_solde') {
                    $query->orderBy('balance', 'ASC');
                }
            }

            if ($request->account_name_sort !== 'all') {
                if ($request->account_name_sort === 'asc') {
                    $query->orderBy('name', 'ASC');
                } elseif ($request->account_name_sort === 'desc') {
                    $query->orderBy('name', 'DESC');
                }
            }

            if ($request->transactions_limit !== 'all') {
                if ($request->transactions_limit === 'asc') {
                    $query->orderBy('total_monthly_transactions', 'ASC');
                } elseif ($request->transactions_limit === 'desc') {
                    $query->orderBy('total_monthly_transactions', 'DESC');
                }
            }
            // Paginate the results
            $sortedData = $query->paginate(50);
            return $sortedData;
        }
        if (Auth::user()->hasRole('operator')) {
            $query = Account::with('user')->where('user_id', Auth::id());

            // Sorting conditions
            if ($request->type !== 'all') {
                $query->where('type', $request->type);
            }

            if ($request->operator !== 'all') {
                // $query->where('user_id', $request->operator);
                $query;
            }

            if ($request->solde_sort !== 'all') {
                if ($request->solde_sort === 'max_solde') {
                    $query->orderBy('balance', 'DESC');
                } elseif ($request->solde_sort === 'min_solde') {
                    $query->orderBy('balance', 'ASC');
                }
            }

            if ($request->account_name_sort !== 'all') {
                if ($request->account_name_sort === 'asc') {
                    $query->orderBy('name', 'ASC');
                } elseif ($request->account_name_sort === 'desc') {
                    $query->orderBy('name', 'DESC');
                }
            }

            if ($request->transactions_limit !== 'all') {
                if ($request->transactions_limit === 'asc') {
                    $query->orderBy('total_monthly_transactions', 'ASC');
                } elseif ($request->transactions_limit === 'desc') {
                    $query->orderBy('total_monthly_transactions', 'DESC');
                }
            }
            // Paginate the results
            $sortedData = $query->paginate(50);

            return $sortedData;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function show(Account $account)
    {
        // return Account::with(['user'])->findOrFail($account->id);
        return Account::select('accounts.*',
        'users.first_name as user_first_name',
        'users.last_name as user_last_name')
        ->leftJoin('users', 'users.id', '=', 'accounts.user_id')
        ->findOrFail($account->id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Account $account)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'type' => 'required',
            'category' => 'required',
            'user_id' => 'required',
        ]);

        $account = Account::findOrFail($account->id);
        $account->update(['name' => $request->name]);
        $account->update(['phone' => $request->phone]);
        $account->update(['type' => $request->type]);
        $account->update(['balance' => $request->balance]);
        $account->update(['category' => $request->category]);
        $account->update(['user_id' => $request->user_id]);
        if($request->full_wallet == true){
            $account->update(['full_wallet' => 1]);
        }else{
            $account->update(['full_wallet' => 0]);
        }
        return Account::with(['user'])->orderBy('created_at', 'DESC')->paginate(50);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function destroy(Account $account)
    {
        $account = Account::findOrFail($account->id);
        Account::destroy($account->id);
    }
}