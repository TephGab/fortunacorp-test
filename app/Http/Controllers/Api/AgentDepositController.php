<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use App\Models\AgentDeposit;
use Illuminate\Http\Request;
use App\Models\SystemAccount;
use App\Models\AgentInvestment;
use App\Models\AgentDebtDeposit;
use App\Events\AgentDepositEvent;
use App\Models\SystemBankAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Rules\CheckIfAmountLessThenDebt;
use App\Rules\CheckifAmountNotEqualZero;
use App\Services\AgentDeposits\AgentDepositStoreService;
use App\Services\AgentDeposits\AgentDepositConfirmService;

class AgentDepositController extends Controller
{
    protected $agentDepositStoreService;
    protected $agentDepositConfirmService;
    private $currMonth;
    private $currYear;

    public function __construct(AgentDepositStoreService $agentDepositStoreService, AgentDepositConfirmService $agentDepositConfirmService)
    {
        $this->agentDepositStoreService = $agentDepositStoreService;
        $this->agentDepositConfirmService = $agentDepositConfirmService;
        $this->currMonth = Carbon::now()->month;
        $this->currYear = Carbon::now()->year;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->hasRole('super-admin')) {
            return AgentDeposit::with(['user'])
                ->where('status', '!=', 'canceled')
                ->leftJoin('users as envoy', 'agent_deposits.envoy_id', '=', 'envoy.id')
                ->select(
                    'agent_deposits.*',
                    //Envoy infos
                    'envoy.id as envoy_id',
                    'envoy.code as envoy_code',
                    'envoy.first_name as envoy_first_name',
                    'envoy.last_name as envoy_last_name',
                    'envoy.email as envoy_email',
                )->orderBy('created_at', 'DESC')->paginate(50);
        }
        if (Auth::user()->hasRole('agent')) {
            return AgentDeposit::with(['user'])->whereRelation('user', 'id', '=', Auth::id())
                ->where('status', '!=', 'canceled')
                ->leftJoin('users as envoy', 'agent_deposits.envoy_id', '=', 'envoy.id')
                ->select(
                    'agent_deposits.*',
                    //Envoy infos
                    'envoy.id as envoy_id',
                    'envoy.code as envoy_code',
                    'envoy.first_name as envoy_first_name',
                    'envoy.last_name as envoy_last_name',
                    'envoy.email as envoy_email',
                )->orderBy('created_at', 'DESC')->paginate(50);
        }
        if (Auth::user()->hasRole('envoy')) {
            return AgentDeposit::with(['user'])
                ->where('status', '!=', 'canceled')
                ->leftJoin('users as envoy', 'agent_deposits.envoy_id', '=', 'envoy.id')
                ->where('agent_deposits.envoy_id', '=', Auth::id())
                ->select(
                    'agent_deposits.*',
                    //Envoy infos
                    'envoy.id as envoy_id',
                    'envoy.code as envoy_code',
                    'envoy.first_name as envoy_first_name',
                    'envoy.last_name as envoy_last_name',
                    'envoy.email as envoy_email',
                )->orderBy('created_at', 'DESC')->paginate(50);
            // return Replenishment::where('envoy_id', Auth::id())->orderBy('created_at', 'DESC')->paginate(50);
        }
        if (Auth::user()->hasRole('system-engineer')) {
            return AgentDeposit::with(['user'])
                ->where('status', '!=', 'canceled')
                ->leftJoin('users as envoy', 'agent_deposits.envoy_id', '=', 'envoy.id')
                ->select(
                    'agent_deposits.*',
                    //Envoy infos
                    'envoy.id as envoy_id',
                    'envoy.code as envoy_code',
                    'envoy.first_name as envoy_first_name',
                    'envoy.last_name as envoy_last_name',
                    'envoy.email as envoy_email',
                )->orderBy('created_at', 'DESC')->paginate(50);
        }
    }

    public function searchByName(Request $request)
    {
        if ($request->input('name') !== null) {
            // Sanitize and validate the input
            $agentName = str_replace(' ', '', $request->input('name'));

            return AgentDeposit::with(['user'])
                ->leftJoin('users', 'agent_deposits.user_id', '=', 'users.id')
                ->where(function ($query) use ($agentName) {
                    $query->whereRaw("REPLACE(users.first_name, ' ', '') LIKE '%" . $agentName . "%'")
                        ->orWhereRaw("REPLACE(users.last_name, ' ', '') LIKE '%" . $agentName . "%'");
                })->select(
                    'agent_deposits.*',
                    // User infos
                    'users.id as user_id',
                    'users.first_name as user_first_name',
                    'users.last_name as user_last_name'
                )
                ->orderBy('agent_deposits.created_at', 'DESC')
                ->paginate(50);
        } else {
            // Handle the case when 'number' is null
            return response()->json(['error' => 'Number parameter is missing.'], 400);
        }
    }

    public function getAgentDepositDetails(Request $request)
    {
        return AgentDeposit::with(['user'])->findOrFail($request->deposit_id);
    }

    public function getEnvoyDeposit(Request $request)
    {
        $agentDep = AgentDeposit::with(['user'])->findOrFail($request->id);
        return User::with(['user_account', 'roles', 'permissions'])->findOrFail($agentDep->envoy_id);
    }

    public function checkPendingDeposit(Request $request)
    {
        // $pendingDepositCount = AgentDeposit::where('user_id', $request->id)->where('status', 'pending')->count();
        return AgentDeposit::where('user_id', $request->id)->where('status', 'pending')->first();

       // return response()->json(["pendingDepositCount" => $pendingDepositCount, "pendingDeposit" => $pendingDeposit]);
    }

    public function confirmAgentDeposit(Request $request)
    {
        $agentDeposit = AgentDeposit::findOrFail($request->agent_deposit_id);
        $agent = User::with(['user_account'])->findOrFail($agentDeposit->sender_id);
        $envoy = User::with(['user_account'])->findOrFail($agentDeposit->envoy_id);
     
        return $this->agentDepositConfirmService->agentDepositConfirm($agentDeposit, $agent, $envoy);

        //Create Envent
        // $agent = User::with(['user_account'])->findOrFail($agentDeposit->sender_id);
        // event(new AgentDepositEvent($agentDeposit, $agent));

        // return AgentDeposit::with(['user'])->findOrFail($request->agent_deposit_id);
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
        $envoy = User::with(['user_account'])->findOrFail($request->envoy_id);
        $systemAccount = SystemAccount::first();
       
        return $this->agentDepositStoreService->agentDepositStore($request, $user, $envoy, $systemAccount);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AgentDeposit  $agentDeposit
     * @return \Illuminate\Http\Response
     */
    public function show(AgentDeposit $agentDeposit)
    {
        return 'ok' . $agentDeposit->id;
        // $agentDeposit = AgentDeposit::with(['user'])->findOrFail($agentDeposit->id);
        // return response()->json($agentDeposit);
    }

    public function getAgentDepositsCount()
    {
        $pendingAgentDepositCount = AgentDeposit::where('status', 'pending')->count();
        $completedAgentDepositCount = AgentDeposit::where('status', 'completed')->count();
        $allConfirmedAgentDepositCount = AgentDeposit::where('status', 'confirmed')->count();

        return [
            "pendingAgentDepositCount" => $pendingAgentDepositCount, "completedAgentDepositCount" => $completedAgentDepositCount,
            "allConfirmedAgentDepositCount" => $allConfirmedAgentDepositCount
        ];
    }

    public function cancelAgentDeposit(Request $request)
    {
        if (Auth::user()->hasRole('agent')) {
            $agentDeposit = AgentDeposit::findOrFail($request->deposit_id);

            if ($agentDeposit->status == 'pending' && $agentDeposit->confirmed_by_receiver == 0 && $agentDeposit->confirmed_by_envoy == 0) {
                $agentDeposit->update(['status' => 'canceled']);
            } else {
                return response()->json([
                    'message' => 'Deposit is already in process',
                    'errors' => [
                        'deposit_in_process' => [
                            'Deposit is already in process!'
                        ]
                    ]
                ], 400);
            }
            return AgentDeposit::with(['user'])->whereRelation('user', 'id', '=', Auth::id())
                ->where('status', '!=', 'canceled')
                ->leftJoin('users as envoy', 'agent_deposits.envoy_id', '=', 'envoy.id')
                ->select(
                    'agent_deposits.*',
                    //Envoy infos
                    'envoy.id as envoy_id',
                    'envoy.code as envoy_code',
                    'envoy.first_name as envoy_first_name',
                    'envoy.last_name as envoy_last_name',
                    'envoy.email as envoy_email',
                )->orderBy('created_at', 'DESC')->paginate(50);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AgentDeposit  $agentDeposit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AgentDeposit $agentDeposit)
    {
        if (Auth::user()->hasRole('agent')) {

            if ($request->deposit_type == 'user_account') {
                $request->validate([
                    'envoy_id' => 'required',
                    'amount' => [
                        'required',
                        new CheckifAmountNotEqualZero()
                    ],
                ]);

                $agentDeposit = AgentDeposit::findOrFail($request->deposit_id);
                $agent = User::with(['user_account'])->findOrFail($request->agent_id);

                if ($agentDeposit->status == 'pending' && $agentDeposit->confirmed_by_envoy == 0 && $agentDeposit->confirmed_by_receiver == 0) {
                    $agentDeposit->update(['amount' => $request->amount]);
                    $agentDeposit->update(['envoy_id' => $request->envoy_id]);
                }

                return AgentDeposit::with(['user'])->whereRelation('user', 'id', '=', Auth::id())
                    ->where('status', '!=', 'canceled')
                    ->leftJoin('users as envoy', 'agent_deposits.envoy_id', '=', 'envoy.id')
                    ->select(
                        'agent_deposits.*',
                        //Envoy infos
                        'envoy.id as envoy_id',
                        'envoy.code as envoy_code',
                        'envoy.first_name as envoy_first_name',
                        'envoy.last_name as envoy_last_name',
                        'envoy.email as envoy_email',
                    )->orderBy('created_at', 'DESC')->paginate(50);
            }
        }
    }

    public function updateDeposit(Request $request)
    {
        if (Auth::user()->hasRole('agent')) {
            if ($request->deposit_type == 'user_account') {
                $request->validate([
                    'envoy_id' => 'required',
                    'amount' => [
                        'required',
                        new CheckifAmountNotEqualZero()
                    ],
                ]);

                $agentDeposit = AgentDeposit::findOrFail($request->deposit_id);
            
                if ($agentDeposit->status == 'pending' && $agentDeposit->confirmed_by_envoy == 0 && $agentDeposit->confirmed_by_receiver == 0) {
                    $agentDeposit->update(['amount' => $request->amount]);
                    $agentDeposit->update(['envoy_id' => $request->envoy_id]);
                }else{
                    return response()->json([
                        'message' => 'Deposit is already in process',
                        'errors' => [
                            'deposit_in_process' => [
                                'Deposit is already in process!'
                            ]
                        ]
                    ], 400);
                }

                $agent = User::with(['user_account'])->findOrFail($request->agent_id);
                event(new AgentDepositEvent($agentDeposit, $agent));

                return AgentDeposit::with(['user'])->whereRelation('user', 'id', '=', Auth::id())
                    ->where('status', '!=', 'canceled')
                    ->leftJoin('users as envoy', 'agent_deposits.envoy_id', '=', 'envoy.id')
                    ->select(
                        'agent_deposits.*',
                        //Envoy infos
                        'envoy.id as envoy_id',
                        'envoy.code as envoy_code',
                        'envoy.first_name as envoy_first_name',
                        'envoy.last_name as envoy_last_name',
                        'envoy.email as envoy_email',
                    )->orderBy('created_at', 'DESC')->paginate(50);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AgentDeposit  $agentDeposit
     * @return \Illuminate\Http\Response
     */
    public function destroy(AgentDeposit $agentDeposit)
    {
        //
    }
}