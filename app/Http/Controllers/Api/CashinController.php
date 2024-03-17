<?php

namespace App\Http\Controllers\Api;

use App\Models\Rate;
use App\Models\Cashin;
use App\Models\Account;
use App\Models\Provider;
use Illuminate\Http\Request;
use App\Models\SystemAccount;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\AccountResource;
use App\Http\Requests\CashinFormRequest;
use App\Models\CashinActivit;
use App\Models\User;

class CashinController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Cashin::with(['user', 'account', 'provider'])->orderBy('created_at', 'DESC')->paginate(20);
    }

    public function getAccountsCashin(Request $request)
    {
        return AccountResource::collection(Account::where('type', $request->account_type)->get());
    }

    public function checkIfProviderExist(Request $request)
    {
        return Provider::where('phone', $request->phone)->get();
    }

    public function cashinSort(Request $request)
    {
       if($request->type == 'all'){
            if($request->solde_sort == 'shuffled'){
                return Cashin::with(['user', 'account', 'provider'])->orderBy('created_at', 'DESC')->paginate(20);
            }
            if($request->solde_sort == 'max_solde'){
                return Cashin::with(['user', 'account', 'provider'])->orderBy('amount', 'DESC')->paginate(20);
            }
            if($request->solde_sort == 'min_solde'){
                return Cashin::with(['user', 'account', 'provider'])->orderBy('amount', 'ASC')->paginate(20);
            }     
       }
       if($request->type == 'moncash' || $request->type == 'natcash'){
            if($request->solde_sort == 'shuffled'){
                return Cashin::with(['user', 'account', 'provider'])->where('type', $request->type)->orderBy('created_at', 'DESC')->paginate(20);
            }
            if($request->solde_sort == 'max_solde'){
                return Cashin::with(['user', 'account', 'provider'])->where('type', $request->type)->orderBy('amount', 'DESC')->paginate(20);
            }
            if($request->solde_sort == 'min_solde'){
                return Cashin::with(['user', 'account', 'provider'])->where('type', $request->type)->orderBy('amount', 'ASC')->paginate(20);
            }
       }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CashinFormRequest $request)
    {
        try {
            DB::beginTransaction();

            // Check if provider exist
            $checkProvider = Provider::where('phone', $request->provider_phone)->first();
            $account = Account::findOrFail($request->account_id);
            // Get system_account
            $systemAccount = SystemAccount::findOrFail(1);
            $admin = User::with(['user_account'])->findOrFail(Auth::id());

            define('CURRENT_ADMIN_BALANCE', $admin->user_account->referral_commissions);
            define('NEW_ADMIN_BALANCE', CURRENT_ADMIN_BALANCE);

            define('CURRENT_ACCOUNT_BALANCE', $account->balance);
            define('NEW_ACCOUNT_BALANCE', CURRENT_ACCOUNT_BALANCE + ($request->amount / $request->rate));

            define('CURRENT_SYSTEM_DEBT', $systemAccount->depts);
            define('NEW_SYSTEM_DEBT', CURRENT_SYSTEM_DEBT + $request->amount);

            define('CURRENT_SYSTEM_FUND', $systemAccount->funds);
            define('NEW_SYSTEM_FUND', CURRENT_SYSTEM_FUND);

        if ($checkProvider) {
            $checkProvider->update(['claim' => $checkProvider->claim + $request->amount]);
            $account->update(['balance' => $account->balance + ($request->amount / $request->rate)]);
            // Increase System_account depts
            $systemAccount->update(['depts' => ($systemAccount->depts + $request->amount)]);

            $cashin = new Cashin;
            $cashin->provider_id = $checkProvider->id;
            $cashin->user_id = Auth::id();
            $cashin->account_id = $request->account_id;
            $cashin->amount = $request->amount;
            $cashin->type = $request->type;
            $cashin->operation = $request->operation;
            $cashin->status = $request->status;
            $cashin->rate = $request->rate;
            $cashin->save();

            $cashin_activity = new CashinActivit;
            $cashin_activity->provider_id = $cashin->provider_id;
            $cashin_activity->user_id = $cashin->user_id;
            $cashin_activity->account_id = $cashin->account_id;
            $cashin_activity->amount = $cashin->amount;
            $cashin_activity->type = $cashin->type;
            $cashin_activity->operation = $cashin->operation;
            $cashin_activity->status = $cashin->status;
            $cashin_activity->rate = $cashin->rate;
            $cashin_activity->cashin_id = $cashin->id;

            //
            $cashin_activity->current_admin_balance = CURRENT_ADMIN_BALANCE;
            $cashin_activity->new_admin_balance = NEW_ADMIN_BALANCE;
            $cashin_activity->current_system_debt = CURRENT_SYSTEM_DEBT;
            $cashin_activity->new_system_debt = NEW_SYSTEM_DEBT;
            $cashin_activity->current_provider_claim = $checkProvider->claim;
            $cashin_activity->new_provider_claim = ($checkProvider->claim + $cashin->amount);
            $cashin_activity->current_account_balance = CURRENT_ACCOUNT_BALANCE;
            $cashin_activity->new_account_balance = NEW_ACCOUNT_BALANCE;
            $cashin_activity->current_system_fund = CURRENT_SYSTEM_FUND; 
            $cashin_activity->new_system_fund = NEW_SYSTEM_FUND; 
            $cashin_activity->step = 'registration';
            //
            $cashin_activity->save();

        }else {
            $account->update(['balance' => $account->balance + ($request->amount / $request->rate)]);
            // Increase System_account depts
            $systemAccount->update(['depts' => ($systemAccount->depts + $request->amount)]);

            $provider = new Provider;
            $provider->name = $request->provider_name;
            $provider->phone = $request->provider_phone;
            $provider->email = $request->provider_email;
            $provider->address = $request->provider_address;
            $provider->claim = $request->amount;
            $provider->save();

            $provider->update(['claim' => $provider->claim + $request->amount]);

            $cashin = new Cashin;
            $cashin->provider_id = $provider->id;
            $cashin->user_id = Auth::id();
            $cashin->account_id = $request->account_id;
            $cashin->amount = $request->amount;
            $cashin->type = $request->type;
            $cashin->operation = $request->operation;
            $cashin->status = $request->status;
            $cashin->rate = $request->rate;
            $cashin->save();

            $cashin_activity = new CashinActivit;
            $cashin_activity->provider_id = $cashin->provider_id;
            $cashin_activity->user_id = $cashin->user_id;
            $cashin_activity->account_id = $cashin->account_id;
            $cashin_activity->amount = $cashin->amount;
            $cashin_activity->type = $cashin->type;
            $cashin_activity->operation = $cashin->operation;
            $cashin_activity->status = $cashin->status;
            $cashin_activity->rate = $cashin->rate;
            $cashin_activity->cashin_id = $cashin->id;

            //
            $cashin_activity->current_admin_balance = CURRENT_ADMIN_BALANCE;
            $cashin_activity->new_admin_balance = NEW_ADMIN_BALANCE;
            $cashin_activity->current_system_debt = CURRENT_SYSTEM_DEBT;
            $cashin_activity->new_system_debt = NEW_SYSTEM_DEBT;
            $cashin_activity->current_provider_claim = 0;
            $cashin_activity->new_provider_claim = $provider->claim;
            $cashin_activity->current_account_balance = CURRENT_ACCOUNT_BALANCE;
            $cashin_activity->new_account_balance = NEW_ACCOUNT_BALANCE;
            $cashin_activity->current_system_fund = CURRENT_SYSTEM_FUND; 
            $cashin_activity->new_system_fund = NEW_SYSTEM_FUND; 
            $cashin_activity->step = 'registration';
            //
            $cashin_activity->save();
        }
        DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'An error occurred during cashin. Please try again. Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cashin  $cashin
     * @return \Illuminate\Http\Response
     */
    public function show(Cashin $cashin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cashin  $cashin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cashin $cashin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cashin  $cashin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cashin $cashin)
    {
        //
    }
}
