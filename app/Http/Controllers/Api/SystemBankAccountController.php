<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\SystemAccount;
use App\Models\SystemBankAccount;
use App\Http\Controllers\Controller;
use App\Http\Resources\SystemBankAccountResource;

class SystemBankAccountController extends Controller
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

    public function getSystemBankAccount()
    {
        return SystemBankAccountResource::collection(SystemBankAccount::all());
    }
 
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->input('bankAccounts')){
            $bankAccounts = $request->input('bankAccounts');
            foreach ($bankAccounts as $bankAccaount) {
                $userbankaccount = new SystemBankAccount;
                $userbankaccount->account_name = $bankAccaount['account_name'];
                $userbankaccount->account_number = $bankAccaount['account_number'];
                $userbankaccount->balance = $bankAccaount['account_balance'];
                $userbankaccount->currency = $bankAccaount['currency'];
                $userbankaccount->bank_name = $bankAccaount['bank_name'];
                $userbankaccount->category = 'none';
                $userbankaccount->save();
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SystemAccount  $systemAccount
     * @return \Illuminate\Http\Response
     */
    public function show(SystemAccount $systemAccount)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SystemAccount  $systemAccount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SystemAccount $systemAccount)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SystemAccount  $systemAccount
     * @return \Illuminate\Http\Response
     */
    public function destroy(SystemAccount $systemAccount)
    {
        //
    }
}
