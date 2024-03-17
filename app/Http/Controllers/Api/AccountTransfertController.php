<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Account;
use App\Models\Cashout;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\SystemAccount;
use App\Models\AccountTransfert;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Rules\ValideAccountTransfert;
use App\Http\Resources\AccountResource;
use App\Models\AccountTransfertActivit;
use App\Http\Requests\AccountTransfertFormRequest;

class AccountTransfertController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->hasRole('super-admin') || Auth::user()->hasRole('admin') || Auth::user()->hasRole('system-engineer'))  {
            return AccountTransfert::with('user')->orderBy('created_at', 'DESC')->paginate(50);
        }
        if (Auth::user()->hasRole('operator')) {
            return AccountTransfert::with('user')->where('user_id', Auth::id())->orderBy('created_at', 'DESC')->paginate(50);
        }
    }

    public function searchByName(Request $request)
    {
        if ($request->input('name') !== null) {
            // Sanitize and validate the input
            $agentName = str_replace(' ', '', $request->input('name'));
        
            // Perform the query with TRIM
            $cashouts = Cashout::with(['user'])
            ->join('users', 'users.id', '=', 'cashouts.user_id') // Assuming 'user_id' is the foreign key connecting Cashout and Users tables
            ->where(function ($query) use ($agentName) {
                $query->whereRaw("REPLACE(users.first_name, ' ', '') LIKE '%" . $agentName . "%'")
                      ->orWhereRaw("REPLACE(users.last_name, ' ', '') LIKE '%" . $agentName . "%'");
            })
            ->orderBy('cashouts.created_at', 'DESC') // Assuming 'created_at' is in the Cashout table
            ->paginate(50);        
   
            return $cashouts;
        } else {
            // Handle the case when 'number' is null
            return response()->json(['error' => 'Number parameter is missing.'], 400);
        }
    }

    public function getAccountsTransferts(Request $request)
    {
        if($request->type == 'moncash'){
            return AccountResource::collection(Account::where('type', $request->type)->get());
        }else{
            return AccountResource::collection(Account::where('type', $request->type)->get());
        }
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AccountTransfertFormRequest $request)
    {
        // Get account sender / receiver
        $accountSender = Account::findOrFail($request->account_sender_id);
        $accountReceiver = Account::findOrFail($request->account_receiver_id);
        $systemAccount = SystemAccount::findOrFail(1);
        $user = User::with(['user_account'])->findOrFail(Auth::id());

        // Validation
        $request->validate([
            'amount' => new ValideAccountTransfert($accountSender->balance),
        ]);

        define('CURRENT_USER_BALANCE', Auth::user()->hasRole('operator') ? $user->user_account->profits : 
            (
                Auth::user()->hasRole('super-admin') ? 0 : $user->user_account->referral_commissions
            )
        );
        
        define('NEW_USER_BALANCE', CURRENT_USER_BALANCE);

        define('CURRENT_SENDER_ACCOUNT_BALANCE',$accountSender->balance);
        define('NEW_SENDER_ACCOUNT_BALANCE', CURRENT_SENDER_ACCOUNT_BALANCE - $request->amount);

        define('CURRENT_RECEIVER_ACCOUNT_BALANCE', $accountReceiver->balance);
        define('NEW_RECEIVER_ACCOUNT_BALANCE', CURRENT_RECEIVER_ACCOUNT_BALANCE + $request->amount);

        define('CURRENT_SYSTEM_DEBT', $systemAccount->depts);
        define('NEW_SYSTEM_DEBT', CURRENT_SYSTEM_DEBT);

        define('CURRENT_SYSTEM_FUND', $systemAccount->funds);
        define('NEW_SYSTEM_FUND', CURRENT_SYSTEM_FUND);


        // Transfert amount
        $accountSender->update(['balance' => ($accountSender->balance - $request->amount)]);
        $accountReceiver->update(['balance' => ($accountReceiver->balance + $request->amount)]);

        try {
        DB::beginTransaction();

        $accountTransfert = new AccountTransfert;
        $accountTransfert->amount = $request->amount;
        $accountTransfert->user_id = Auth::id();
        $accountTransfert->account_sender_id = $accountSender->id;
        $accountTransfert->account_sender_name = $accountSender->name;
        $accountTransfert->account_sender_phone = $accountSender->phone;
        $accountTransfert->account_receiver_id = $accountReceiver->id;
        $accountTransfert->account_receiver_name = $accountReceiver->name;
        $accountTransfert->account_receiver_phone = $accountReceiver->phone;
        $accountTransfert->countable = $request->countable;
        $accountTransfert->save();

        $transfert_activity = new AccountTransfertActivit;
        $transfert_activity->amount = $accountTransfert->amount;
        $transfert_activity->user_id = $accountTransfert->user_id;
        $transfert_activity->account_sender_id = $accountTransfert->account_sender_id;
        $transfert_activity->account_sender_name = $accountTransfert->account_sender_name;
        $transfert_activity->account_sender_phone = $accountTransfert->account_sender_phone;
        $transfert_activity->account_receiver_id = $accountTransfert->account_receiver_id;
        $transfert_activity->account_receiver_name = $accountTransfert->account_receiver_name;
        $transfert_activity->account_receiver_phone = $accountTransfert->account_receiver_phone;
        $transfert_activity->account_transfert_id = $accountTransfert->id;
        $transfert_activity->countable = $accountTransfert->countable;

        //
        $transfert_activity->current_user_balance = CURRENT_USER_BALANCE;
        $transfert_activity->new_user_balance = NEW_USER_BALANCE;
        $transfert_activity->current_system_debt = CURRENT_SYSTEM_DEBT;
        $transfert_activity->new_system_debt = NEW_SYSTEM_DEBT;
        $transfert_activity->current_sender_account_balance = CURRENT_SENDER_ACCOUNT_BALANCE;
        $transfert_activity->new_sender_account_balance = NEW_SENDER_ACCOUNT_BALANCE;
        $transfert_activity->current_receiver_account_balance = CURRENT_RECEIVER_ACCOUNT_BALANCE;
        $transfert_activity->new_receiver_account_balance = NEW_RECEIVER_ACCOUNT_BALANCE;
        $transfert_activity->current_system_fund = CURRENT_SYSTEM_FUND; 
        $transfert_activity->new_system_fund = NEW_SYSTEM_FUND; 
        $transfert_activity->step = 'registration';
        //
        $transfert_activity->save();

        if ($request->countable) {
            $today = date('Y-m-d');

            if (date('d', strtotime($today)) === '01') {
                $accountSender->update(['total_monthly_transactions' => 1]);
            }else{
                if ($accountSender->total_monthly_transactions < 50) {
                    $accountSender->update(['total_monthly_transactions' => ($accountSender->total_monthly_transactions + 1)]);
                }else{
                    return response()->json([
                        'message' => 'Account Transfert limit reached',
                        'errors' => [
                            'limit_reached' => [
                                'Account Transfert limit reached!'
                            ]
                        ]
                    ], 400);
                }
            }
        }

        DB::commit();
        return Account::with('user')->orderBy('created_at', 'DESC')->paginate(50);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'An error occurred during transfert. Please try again. Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AccountTransfert  $accountTransfert
     * @return \Illuminate\Http\Response
     */
    public function show(AccountTransfert $accountTransfert)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AccountTransfert  $accountTransfert
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AccountTransfert $accountTransfert)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AccountTransfert  $accountTransfert
     * @return \Illuminate\Http\Response
     */
    public function destroy(AccountTransfert $accountTransfert)
    {
        //
    }
}
