<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Cashout;
use App\Models\UserSort;
use App\Models\AgentLoan;
use App\Models\SendMoney;
use App\Models\ContextSort;
use App\Models\UserActivit;
use App\Models\AgentDeposit;
use App\Models\EnvoyDeposit;
use Illuminate\Http\Request;
use App\Models\AgentLoanRepay;
use App\Models\EnvoyTransfert;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\AgentLoanTransactionRepay;

class UserActivitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    public function getUserActivities(Request $request)
    {        
        $context = 'user_activities';
        $user = User::findOrFail($request->user_id);
        $contextSort = ContextSort::firstOrCreate(['context' => $context]);
        $selectedYear = $request->selected_year;
        $selectedMonth = $request->selected_month; 

        $userSort = $user->userSorts()->whereHas('contextSorts', function ($query) use ($context) {
            $query->where('context', $context);
        })->where('selected_year', $selectedYear)->where('selected_month', $selectedMonth)->first();
      
        if ($userSort) {
            $userSort->touch();
        }
        else {
            $userSort = UserSort::firstOrCreate(['selected_year' => $selectedYear, 'selected_month' => $selectedMonth]);
            $user->userSorts()->attach($userSort->id, ['context_sort_id' => $contextSort->id]);
        }
      
        $userActivities = null;
        $envoy_cashouts = null;
        $envoy_agent_deposits = null;
        $envoy_deposits = null;
        $envoy_send_money = null;
        $envoy_transfert_received = null;
        $envoy_transfert_sent = null;
        $envoy_cashout_confirmed = null;
        $envoy_deposit_confirmed = null;
        $envoy_send_money_confirmed = null;
        $agent_loans = null;
        $envoy_agent_loan_repays = null;

        if (Auth::user()->hasRole('agent')) {
            $userActivities = User::with([
                'transactions' => function ($query) use ($userSort) {
                    $query->where('status', 'completed')
                        ->whereYear('completed_date', '=', $userSort->selected_year)
                        ->whereMonth('completed_date', '=', $userSort->selected_month)
                        ->orderBy('completed_date', 'ASC');
                },
                'transactions.user',
                'transactions.client',
                'transactions.receiver',
                'transactions.operator',
                'transactions.transaction_activits',
                'withdraws' => function ($query) use ($userSort) {
                    $query->where('status', 'confirmed')
                        ->whereYear('receiver_confirmation_date', '=', $userSort->selected_year)
                        ->whereMonth('receiver_confirmation_date', '=', $userSort->selected_month)
                        ->orderBy('receiver_confirmation_date', 'ASC');
                },
                'withdraws.receiver',
                'withdraws.sender',
                'withdraws.envoy',
                'withdraws.send_money_activits',
                'agent_deposits' => function ($query) use ($userSort) {
                    $query->where('status', 'confirmed')
                        ->whereYear('receiver_confirmation_date', '=', $userSort->selected_year)
                        ->whereMonth('receiver_confirmation_date', '=', $userSort->selected_month)
                        ->orderBy('receiver_confirmation_date', 'ASC');
                },
                'agent_deposits.user',
                'agent_deposits.admin',
                'agent_deposits.envoy',
                'agent_deposits.agent_deposit_activits',
                'profit_to_recharges' => function ($query) use ($userSort) {
                    $query->whereYear('completed_date', '=', $userSort->selected_year)
                        ->whereMonth('completed_date', '=', $userSort->selected_month)
                        ->orderBy('completed_date', 'ASC');
                },
                'referrals',
                'referrals.transactions.user',
                'referrals.transactions.client',
                'referrals.transactions.operator',
                'referrals.transactions' => function ($query) use ($userSort) {
                    $query->where('status', 'completed')
                        ->whereYear('completed_date', '=', $userSort->selected_year)
                        ->whereMonth('completed_date', '=', $userSort->selected_month)
                        ->orderBy('completed_date', 'ASC')
                        ->with('transaction_activits');
                },
                'cashouts' => function ($query) use ($userSort) {
                    $query->where('confirmed_by_user', true)
                        ->whereYear('receiver_confirmation_date', '=', $userSort->selected_year)
                        ->whereMonth('receiver_confirmation_date', '=', $userSort->selected_month)
                        ->orderBy('receiver_confirmation_date', 'ASC');
                },
                'cashouts.cashout_activits',
                'agent_loan_repays' => function ($query) use ($userSort) {
                    $query->where('confirmed_by_receiver', true)
                        ->whereYear('receiver_confirmation_date', '=', $userSort->selected_year)
                        ->whereMonth('receiver_confirmation_date', '=', $userSort->selected_month)
                        ->orderBy('receiver_confirmation_date', 'ASC');
                },
                'agent_loan_repays.user',
                'agent_loan_repays.admin',
                'agent_loan_repays.envoy',
                'agent_loan_repays.agent_loan_repay_activits',
                'agent_loan_transaction_repays' => function ($query) use ($userSort) {
                    $query->whereYear('updated_at', '=', $userSort->selected_year)
                        ->whereMonth('updated_at', '=', $userSort->selected_month)
                        ->orderBy('updated_at', 'ASC');
                },
                'agent_loan_transaction_repays.user',
                'agent_loan_transaction_repays.transaction',
            ])->where('id', $request->user_id)->first();

            $agent_loans = AgentLoan::where('receiver_id', $request->user_id)
            ->where('confirmed_by_receiver', true)
            ->whereYear('receiver_confirmation_date', '=', $userSort->selected_year)
            ->whereMonth('receiver_confirmation_date', '=', $userSort->selected_month)
            ->with('admin','receiver','agent_loan_activits')
            ->orderBy('receiver_confirmation_date', 'ASC')
            ->get();
        }
        if (Auth::user()->hasRole('envoy')) {
            $userActivities = User::with([
                'referrals',
                'referrals.transactions.user',
                'referrals.transactions.client',
                'referrals.transactions.operator',
                'referrals.transactions' => function ($query) use ($userSort) {
                    $query->where('status', 'completed')
                        ->whereYear('completed_date', '=', $userSort->selected_year)
                        ->whereMonth('completed_date', '=', $userSort->selected_month)
                        ->orderBy('completed_date', 'ASC')
                        ->with('transaction_activits');
                },
                'cashouts' => function ($query) use ($userSort) {
                    $query->where('confirmed_by_user', true)
                        ->whereYear('receiver_confirmation_date', '=', $userSort->selected_year)
                        ->whereMonth('receiver_confirmation_date', '=', $userSort->selected_month)
                        ->orderBy('receiver_confirmation_date', 'ASC');
                },
                'cashouts.cashout_activits',
            ])->where('id', $request->user_id)->first();

            $envoy_cashouts = Cashout::where('envoy_id', $request->user_id)
            ->where('confirmed_by_envoy', true)
            ->whereYear('envoy_confirmation_date', '=', $userSort->selected_year)
            ->whereMonth('envoy_confirmation_date', '=', $userSort->selected_month)
            ->with('user','admin','envoy','cashout_activits')
            ->orderBy('envoy_confirmation_date', 'ASC')
            ->get();

            $envoy_cashout_confirmed = Cashout::where('envoy_id', $request->user_id)
            ->where('confirmed_by_user', true)
            ->where('status', 'confirmed')
            ->whereYear('receiver_confirmation_date', '=', $userSort->selected_year)
            ->whereMonth('receiver_confirmation_date', '=', $userSort->selected_month)
            ->with('user','admin','envoy','cashout_activits')
            ->orderBy('receiver_confirmation_date', 'ASC')
            ->get();


            $envoy_agent_deposits = AgentDeposit::where('envoy_id', $request->user_id)
            ->where('confirmed_by_receiver', true)
            ->whereYear('receiver_confirmation_date', '=', $userSort->selected_year)
            ->whereMonth('receiver_confirmation_date', '=', $userSort->selected_month)
            ->with('user','admin','envoy','agent_deposit_activits')
            ->orderBy('receiver_confirmation_date', 'ASC')
            ->get();

            $envoy_deposit_confirmed = EnvoyDeposit::where('user_id', $request->user_id)
            ->where('confirmed_by_receiver', true)
            ->whereYear('receiver_confirmation_date', '=', $userSort->selected_year)
            ->whereMonth('receiver_confirmation_date', '=', $userSort->selected_month)
            ->with('admin','envoy_deposit_activits')
            ->orderBy('receiver_confirmation_date', 'ASC')
            ->get();

            $envoy_deposits = EnvoyDeposit::where('user_id', $request->user_id)
            ->whereYear('created_at', '=', $userSort->selected_year)
            ->whereMonth('created_at', '=', $userSort->selected_month)
            ->with('admin','envoy_deposit_activits')
            ->orderBy('created_at', 'ASC')
            ->get();

            $envoy_send_money = SendMoney::where('envoy_id', $request->user_id)
            ->where('confirmed_by_envoy', true)
            ->whereYear('envoy_confirmation_date', '=', $userSort->selected_year)
            ->whereMonth('envoy_confirmation_date', '=', $userSort->selected_month)
            ->with('receiver','sender','send_money_activits')
            ->orderBy('envoy_confirmation_date', 'ASC')
            ->get();
            
            $envoy_send_money_confirmed = SendMoney::where('envoy_id', $request->user_id)
            ->where('confirmed_by_receiver', true)
            ->where('status', 'confirmed')
            ->whereYear('receiver_confirmation_date', '=', $userSort->selected_year)
            ->whereMonth('receiver_confirmation_date', '=', $userSort->selected_month)
            ->with('receiver','sender','send_money_activits')
            ->orderBy('receiver_confirmation_date', 'ASC')
            ->get();

            $envoy_transfert_sent = EnvoyTransfert::where('user_id', $request->user_id)
            ->where('confirmed_by_receiver', true)
            ->whereYear('receiver_confirmation_date', '=', $userSort->selected_year)
            ->whereMonth('receiver_confirmation_date', '=', $userSort->selected_month)
            ->with('user','receiver','envoy_transfert_activits')
            ->orderBy('receiver_confirmation_date', 'ASC')
            ->get();

            $envoy_transfert_received = EnvoyTransfert::where('receiver_id', $request->user_id)
            ->where('confirmed_by_receiver', true)
            ->whereYear('receiver_confirmation_date', '=', $userSort->selected_year)
            ->whereMonth('receiver_confirmation_date', '=', $userSort->selected_month)
            ->with('user','receiver','envoy_transfert_activits')
            ->orderBy('receiver_confirmation_date', 'ASC')
            ->get();

            $envoy_agent_loan_repays = AgentLoanRepay::where('envoy_id', $request->user_id)
            ->where('confirmed_by_envoy', true)
            ->whereYear('envoy_confirmation_date', '=', $userSort->selected_year)
            ->whereMonth('envoy_confirmation_date', '=', $userSort->selected_month)
            ->with('user','envoy','agent_loan_repay_activits')
            ->orderBy('envoy_confirmation_date', 'ASC')
            ->get();

            // $envoy_agent_loan_transaction_repays = AgentLoanTransactionRepay::where('envoy_id', $request->user_id)
            // ->where('confirmed_by_envoy', true)
            // ->whereYear('envoy_confirmation_date', '=', $userSort->selected_year)
            // ->whereMonth('envoy_confirmation_date', '=', $userSort->selected_month)
            // ->with('user','envoy','agent_loan_repay_activits')
            // ->orderBy('envoy_confirmation_date', 'ASC')
            // ->get();
        } 
        if (Auth::user()->hasRole('operator') || Auth::user()->hasRole('envoy') || Auth::user()->hasRole('admin') || Auth::user()->hasRole('system-engineer')) {
            $userActivities = User::with([
                'cashouts' => function ($query) use ($userSort) {
                    $query->where('confirmed_by_user', true)
                        ->whereYear('receiver_confirmation_date', '=', $userSort->selected_year)
                        ->whereMonth('receiver_confirmation_date', '=', $userSort->selected_month)
                        ->orderBy('receiver_confirmation_date', 'ASC');
                },
                'cashouts.cashout_activits',  
            ])->where('id', $request->user_id)->first();
        }
      
        // Merge activities and label with table names
        $activities = collect([]);

        if ($userActivities->transactions) {
            $activities = $activities->merge($userActivities->transactions->map(function ($activity) {
                $activity['table'] = 'transactions';
                return $activity;
            }));
        }

        if ($userActivities->withdraws) {
            $activities = $activities->merge($userActivities->withdraws->map(function ($activity) {
                $activity['table'] = 'withdraws';
                return $activity;
            }));
        }

        if ($userActivities->agent_deposits) {
            $activities = $activities->merge($userActivities->agent_deposits->map(function ($activity) {
                $activity['table'] = 'agent_deposits';
                return $activity;
            }));
        }

        if ($userActivities->profit_to_recharges) {
            $activities = $activities->merge($userActivities->profit_to_recharges->map(function ($activity) {
                $activity['table'] = 'profit_to_recharges';
                return $activity;
            }));
        }

        if ($userActivities->cashouts) {
            $activities = $activities->merge($userActivities->cashouts->map(function ($activity) {
                $activity['table'] = 'cashouts';
                return $activity;
            }));
        }

        if ($userActivities->referrals) {
            $activities = $activities->merge($userActivities->referrals->map(function ($activity) {
                $activity['table'] = 'referrals';
                return $activity;
            }));
        }

        if ($userActivities->agent_loan_repays) {
            $activities = $activities->merge($userActivities->agent_loan_repays->map(function ($activity) {
                $activity['table'] = 'agent_loan_repays';
                return $activity;
            }));
        }

        if ($userActivities->agent_loan_transaction_repays) {
            $activities = $activities->merge($userActivities->agent_loan_transaction_repays->map(function ($activity) {
                $activity['table'] = 'agent_loan_transaction_repays';
                return $activity;
            }));
        }

        if ($envoy_agent_loan_repays) {
            $activities = $activities->merge($envoy_agent_loan_repays->map(function ($activity) {
                $activity['table'] = 'envoy_agent_loan_repays';
                return $activity;
            }));
        }

        if ($agent_loans) {
            $activities = $activities->merge($agent_loans->map(function ($activity) {
                $activity['table'] = 'agent_loans';
                return $activity;
            }));
        }

        if ($envoy_cashouts) {
            $activities = $activities->merge($envoy_cashouts->map(function ($activity) {
                $activity['table'] = 'envoy_cashouts';
                return $activity;
            }));
        }

        if ($envoy_cashout_confirmed) {
            $activities = $activities->merge($envoy_cashout_confirmed->map(function ($activity) {
                $activity['table'] = 'envoy_cashout_confirmed';
                return $activity;
            }));
        }

        if ($envoy_agent_deposits) {
            $activities = $activities->merge($envoy_agent_deposits->map(function ($activity) {
                $activity['table'] = 'envoy_agent_deposits';
                return $activity;
            }));
        }

        if ($envoy_send_money) {
            $activities = $activities->merge($envoy_send_money->map(function ($activity) {
                $activity['table'] = 'envoy_send_money';
                return $activity;
            }));
        }

        if ($envoy_send_money_confirmed) {
            $activities = $activities->merge($envoy_send_money_confirmed->map(function ($activity) {
                $activity['table'] = 'envoy_send_money_confirmed';
                return $activity;
            }));
        }

        if ($envoy_deposits) {
            $activities = $activities->merge($envoy_deposits->map(function ($activity) {
                $activity['table'] = 'envoy_deposits';
                return $activity;
            }));
        }

        if ($envoy_deposit_confirmed) {
            $activities = $activities->merge($envoy_deposit_confirmed->map(function ($activity) {
                $activity['table'] = 'envoy_deposit_confirmed';
                return $activity;
            }));
        }

        if ($envoy_transfert_sent) {
            $activities = $activities->merge($envoy_transfert_sent->map(function ($activity) {
                $activity['table'] = 'envoy_transfert_sent';
                return $activity;
            }));
        }

        if ($envoy_transfert_received) {
            $activities = $activities->merge($envoy_transfert_received->map(function ($activity) {
                $activity['table'] = 'envoy_transfert_received';
                return $activity;
            }));
        }

        //Sort activities by date
        $sortedActivities = $activities->sortBy(function ($activity) {
            return $activity['completed_date'] ?? $activity['receiver_confirmation_date'];
        }, SORT_REGULAR, false);

        // $sortedActivities = $activities->sortByDesc(function ($activity) {
        //     return $activity['completed_date'] ?? $activity['receiver_confirmation_date'];
        // });        

        return response()->json($sortedActivities);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserActivit  $userActivit
     * @return \Illuminate\Http\Response
     */
    public function show(UserActivit $userActivit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserActivit  $userActivit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserActivit $userActivit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserActivit  $userActivit
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserActivit $userActivit)
    {
        //
    }
}
