<?php

namespace App\Http\Controllers\Api;

use App\Models\UserAccount;
use Illuminate\Http\Request;
use App\Models\UserBankAccount;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserBankAccountResource;
use Illuminate\Support\Facades\Auth;

class UserBankAccountController extends Controller
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

    public function getUserBankAccount()
    {
        return UserBankAccountResource::collection(UserBankAccount::where('user_id', Auth::id())->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         if(count($request->input('bankAccounts')) != 0){
            $bankAccounts = $request->input('bankAccounts');
            foreach ($bankAccounts as $bankAccaount) {
                $userbankaccount = new UserBankAccount;
                $userbankaccount->account_name = $bankAccaount['account_name'];
                $userbankaccount->account_number = $bankAccaount['account_number'];
                $userbankaccount->balance = $bankAccaount['account_balance'];
                $userbankaccount->currency = $bankAccaount['currency'];
                $userbankaccount->bank_name = $bankAccaount['bank_name'];
                $userbankaccount->category = 'none';
                $userbankaccount->user_id = $request->user_id;
                $userbankaccount->save();
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserAccount  $userAccount
     * @return \Illuminate\Http\Response
     */
    public function show(UserAccount $userAccount)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserAccount  $userAccount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserAccount $userAccount)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserAccount  $userAccount
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserAccount $userAccount)
    {
        //
    }
}
