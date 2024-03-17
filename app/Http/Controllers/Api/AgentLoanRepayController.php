<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\SystemAccount;
use App\Models\AgentLoanRepay;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Rules\CheckAgentLoanPaymentStore;
use App\Services\AgentLoanRepay\AgentLoanRepayStoreService;
use App\Services\AgentLoanRepay\AgentLoanRepayConfirmService;

class AgentLoanRepayController extends Controller
{
    protected $agentLoanRepayStoreService;
    public $agentLoanRepayConfirmService;
   
    public function __construct(AgentLoanRepayStoreService $agentLoanRepayStoreService, AgentLoanRepayConfirmService $agentLoanRepayConfirmService)
    {
        $this->agentLoanRepayStoreService = $agentLoanRepayStoreService;
        $this->agentLoanRepayConfirmService = $agentLoanRepayConfirmService;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->hasRole('super-admin') || Auth::user()->hasRole('system-engineer')) {
            return AgentLoanRepay::with(['user', 'admin', 'envoy', 'agent_loan_repay_activits'])
                ->where('status', '!=', 'canceled')
                ->orderBy('created_at', 'DESC')->paginate(50);
        }
        if (Auth::user()->hasRole('agent')) {
            return AgentLoanRepay::with(['user', 'admin', 'envoy', 'agent_loan_repay_activits'])
                ->where('status', '!=', 'canceled')
                ->where('user_id', '=', Auth::id())
                ->orderBy('created_at', 'DESC')->paginate(50);
        }
        if (Auth::user()->hasRole('envoy')) {
            return AgentLoanRepay::with(['user', 'admin', 'envoy', 'agent_loan_repay_activits'])
                ->where('status', '!=', 'canceled')
                ->where('envoy_id', '=', Auth::id())
                ->orderBy('created_at', 'DESC')->paginate(50);
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
        $user = User::with(['user_account'])->findOrFail(Auth::id());
        
        $request->validate([
            'amount' => ['required', new CheckAgentLoanPaymentStore($user->user_account->depts)],
            'envoy_id' => 'required']);

        $envoy = User::with(['user_account'])->findOrFail($request->envoy_id);
        $systemAccount = SystemAccount::first();
       
        return $this->agentLoanRepayStoreService->agentLoanRepayStore($request, $user, $envoy, $systemAccount);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AgentLoanRepay  $agentLoanRepay
     * @return \Illuminate\Http\Response
     */

    public function show(AgentLoanRepay $agentLoanRepay, $id)
    {
        return AgentLoanRepay::with(['user', 'envoy', 'agent_loan_repay_activits'])->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AgentLoanRepay  $agentLoanRepay
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AgentLoanRepay $agentLoanRepay)
    {
        //
    }

    public function confirmAgentLoanRepay(Request $request)
    {
        $loan = AgentLoanRepay::findOrFail($request->agent_loan_repay_id);
        $agent = User::with(['user_account'])->findOrFail($loan->user_id);
        $envoy = User::with(['user_account'])->findOrFail($loan->envoy_id);
     
        return $this->agentLoanRepayConfirmService->loanPaymentConfirm($loan, $agent, $envoy);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AgentLoanRepay  $agentLoanRepay
     * @return \Illuminate\Http\Response
     */
    public function destroy(AgentLoanRepay $agentLoanRepay)
    {
        //
    }
}
