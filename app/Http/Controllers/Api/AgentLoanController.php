<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use App\Models\AgentLoan;
use Illuminate\Http\Request;
use App\Models\AgentLoanActivit;
use App\Rules\CheckAgentLoanStore;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AgentLoanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->hasRole('super-admin') || Auth::user()->hasRole('admin') || Auth::user()->hasRole('system-engineer')) {
            return AgentLoan::with(['admin','receiver'])->orderBy('created_at', 'DESC')->paginate(50);
        }
        if (Auth::user()->hasRole('agent')) {
            return AgentLoan::with(['admin','receiver'])->where('receiver_id', Auth::id())->orderBy('created_at', 'DESC')->paginate(50);
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
        $request->validate(['agent_id' => 'required']);

        $agent = User::with('user_account', 'agent_loans.agent_loan_activits')->findOrFail($request->agent_id);

        $request->validate([
            'amount' => [
                'required',
                new CheckAgentLoanStore($agent->user_account->depts)
            ],]);

        try{
            DB::beginTransaction();
            $loan = new AgentLoan;
            $loan->amount = $request->amount;
            $loan->status = 'pending';
            $loan->type = 'start_loan';
            $loan->currency = $request->currency;
            $loan->user_id = Auth::id();
            $loan->receiver_id = $request->agent_id;
            $loan->comment = $request->comment;

            if ($agent->depts > 0) {
                $loan->loan_percentage = $agent->loan_percentage;
            } else {
                $loan->loan_percentage = $request->percentage;
                $agent->update(['loan_percentage' => $request->percentage]);
            }
            
            $loan->save();

            //History
            $loan_history = new AgentLoanActivit;
            $loan_history->amount = $loan->amount;
            $loan_history->status = $loan->status;
            $loan_history->type = $loan->type;
            $loan_history->currency = $loan->currency;
            $loan_history->user_id = $loan->user_id;
            $loan_history->receiver_id = $loan->receiver_id;
            $loan_history->comment = $loan->comment;
            $loan_history->loan_percentage = $loan->loan_percentage;
            $loan_history->confirmed_by_receiver = false;
            $loan_history->payment_progress = 0;
            $loan_history->payment_status = 'unpaid';
            $loan_history->commission_payment = 0;
            $loan_history->deposit_payment = 0;
            $loan_history->agent_loan_id = $loan->id;
            //
            $loan_history->current_receiver_debt = $agent->user_account->depts;
            $loan_history->current_receiver_commission = $agent->user_account->profits;
            $loan_history->current_receiver_referral_commission = $agent->user_account->referral_commissions;
            $loan_history->current_receiver_investment = $agent->user_account->investments;
            $loan_history->current_system_claim = 0;
            $loan_history->current_system_debt = 0;
            $loan_history->current_system_fund = 0.0;
            $loan_history->new_system_fund = 0.0;
            $loan_history->step = Auth::user()->getRoleNames()->first() . '-initialization';
            //
            $loan_history->save();
            //History
            DB::commit();
            return AgentLoan::with(['admin','receiver'])->orderBy('created_at', 'DESC')->paginate(50);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'An error occurred during agent loan process. Please try again. Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AgentLoan  $agentLoan
     * @return \Illuminate\Http\Response
     */
    public function show(AgentLoan $agentLoan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AgentLoan  $agentLoan
     * @return \Illuminate\Http\Response
     */
    
    public function update(Request $request, AgentLoan $agentLoan)
    {
        //
    }

    public function confirmAgentLoan(Request $request)
    {
        $request->validate([
            'loan_id' => 'required',
            'agent_id' => 'required',
        ]);

        $loan = AgentLoan::with('admin', 'receiver', 'agent_loan_activits')->findOrFail($request->loan_id);
        $agent = User::with('user_account', 'agent_loans.agent_loan_activits')->findOrFail($request->agent_id);
        $laravelTime = config('app.timezone');
        Carbon::setTestNow(Carbon::now($laravelTime));

        try{
           if ($loan->status == 'pending' && Auth::id() == $agent->id) {
                DB::beginTransaction();
                
                $loan->update(['status' => 'confirmed',
                               'confirmed_by_receiver' => true,
                               'receiver_confirmation_date' => Carbon::now($laravelTime)]);
                
                //History
                $loan_history = new AgentLoanActivit;
                $loan_history->amount = $loan->amount;
                $loan_history->status = $loan->status;
                $loan_history->type = $loan->type;
                $loan_history->currency = $loan->currency;
                $loan_history->user_id = $loan->user_id;
                $loan_history->receiver_id = $loan->receiver_id;
                $loan_history->comment = $loan->comment;
                $loan_history->loan_percentage = $loan->loan_percentage;
                $loan_history->confirmed_by_receiver = true;
                $loan_history->payment_progress = 0;
                $loan_history->payment_status = 'unpaid';
                $loan_history->commission_payment = 0;
                $loan_history->deposit_payment = 0;
                $loan_history->receiver_confirmation_date = Carbon::now($laravelTime);
                $loan_history->agent_loan_id = $loan->id;
                //
                $loan_history->current_receiver_debt = $agent->user_account->depts;
                $loan_history->new_receiver_debt = $agent->user_account->depts + $loan->amount;
                $loan_history->current_receiver_commission = $agent->user_account->profits;
                $loan_history->new_receiver_commission = $agent->user_account->profits;
                $loan_history->current_receiver_referral_commission = $agent->user_account->referral_commissions;
                $loan_history->new_receiver_referral_commission = $agent->user_account->referral_commissions;
                $loan_history->current_receiver_investment = $agent->user_account->investments;
                $loan_history->new_receiver_investment = $agent->user_account->investments + $loan->amount;
                $loan_history->current_system_claim = 0;
                $loan_history->new_system_claim = 0;
                $loan_history->current_system_debt = 0;
                $loan_history->new_system_debt = 0;
                $loan_history->current_system_fund = 0.0;
                $loan_history->new_system_fund = 0.0;
                $loan_history->step = Auth::user()->getRoleNames()->first() . '-confirmation';
                //
                $loan_history->save();
                //History

                $agent->user_account->update([
                    'depts' => ($agent->user_account->depts + $loan->amount),
                    'investments' => ($agent->user_account->investments + $loan->amount)]);
                // $agent->user_account->update(['investments' => ($agent->user_account->investments + $loan->amount)]);

                DB::commit();
                return AgentLoan::with(['admin','receiver'])->where('receiver_id', Auth::id())->orderBy('created_at', 'DESC')->paginate(50);
           }else{
                return response()->json(['error' => 'An error occurred during agent loan process. Please try again. Error: '], 500);
           }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'An error occurred during agent loan process. Please try again. Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AgentLoan  $agentLoan
     * @return \Illuminate\Http\Response
     */
    public function destroy(AgentLoan $agentLoan)
    {
        //
    }
}
