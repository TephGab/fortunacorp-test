<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Cashout;
use App\Events\CashoutEvent;
use Illuminate\Http\Request;
use App\Models\SystemAccount;
use App\Models\SystemBankAccount;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\Cashouts\CashoutStoreService;
use App\Services\Cashouts\CashoutConfirmService;
use App\Services\Cashouts\CashoutCompletedByAdminService;

class CashoutController extends Controller
{

    protected $cashoutStoreService;
    protected $cashoutCompletedByAdminService;
    protected $cashoutConfirmService;
    private $currMonth;
    private $currYear;

    public function __construct(CashoutStoreService $cashoutStoreService, CashoutCompletedByAdminService $cashoutCompletedByAdminService, CashoutConfirmService $cashoutConfirmService)
    {
        $this->cashoutStoreService = $cashoutStoreService;
        $this->cashoutCompletedByAdminService = $cashoutCompletedByAdminService;
        $this->cashoutConfirmService = $cashoutConfirmService;
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
            return Cashout::with(['user'])->orderBy('created_at', 'DESC')->paginate(50);
        }
        if (Auth::user()->hasRole('system-engineer')) {
            return Cashout::with(['user'])->orderBy('created_at', 'DESC')->paginate(50);
        }
        if (Auth::user()->hasRole('envoy')) {
            return Cashout::with(['user'])->where('envoy_id', Auth::id())->orWhere('user_id', Auth::id())->orderBy('created_at', 'DESC')->paginate(50);
        }
        if (Auth::user()->hasRole('agent') || Auth::user()->hasRole('operator') || Auth::user()->hasRole('admin')) {
            return Cashout::with(['user'])->where('user_id', Auth::id())->orderBy('created_at', 'DESC')->paginate(50);
        }
    }

    public function searchByName(Request $request)
    {
        if (Auth::user()->hasRole('super-admin')) {
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
        if (Auth::user()->hasRole('envoy')) {
            if ($request->input('name') !== null) {
                // Sanitize and validate the input
                $agentName = str_replace(' ', '', $request->input('name'));

                // Perform the query with TRIM
                $cashouts = Cashout::with(['user'])
                    ->where('envoy_id', Auth::id())
                    ->orWhere('user_id', Auth::id())
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
    }

    public function getCashoutDetails(Request $request)
    {
        return Cashout::with(['user.user_account'])->findOrFail($request->cashout_id);
    }

    public function completeCashout(Request $request)
    {
        $cashout = Cashout::findOrFail($request->cashout_id);
        $envoy = $cashout->user_role == 'envoy' ? User::with(['user_account'])->findOrFail($cashout->user_id) : User::with(['user_account'])->findOrFail($request->envoy_id);
        $user = User::with(['user_account'])->findOrFail($cashout->user_id);

        return $this->cashoutCompletedByAdminService->cashoutCompletedByAdmin($request, $cashout, $envoy, $user);
    }

    public function confirmCashout(Request $request)
    {
        $cashout = Cashout::findOrFail($request->cashout_id);
        $user = User::with(['user_account'])->findOrFail($cashout->user_id);
        $envoy = User::with(['user_account'])->findOrFail($cashout->envoy_id);
        $systemAccount = SystemAccount::first();
        
        return $this->cashoutConfirmService->cashoutConfirmService($cashout, $user, $envoy, $systemAccount);
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
        $systemAccount = SystemAccount::first();

        return $this->cashoutStoreService->cashoutStore($request, $user, $systemAccount);
    }

    public function checkPendingCashout(Request $request)
    {
        return Cashout::where('user_id', $request->id)->where(function ($query) {
            $query->where('status', 'pending')
                ->orWhere('status', 'completed');
        })->first();

        // $pendingCashoutCount = Cashout::where('user_id', $request->id)->where('status', 'pending')->count();
        // $pendingCashout = Cashout::where('user_id', $request->id)->where('status', 'pending')->get();
        // $completedCashoutCount = Cashout::where('user_id', $request->id)->where('status', 'completed')->count();
        // $completedCashout = Cashout::where('user_id', $request->id)->where('status', 'completed')->get();
        // $confirmedCashoutCount = Cashout::where('user_id', $request->id)->where('status', 'confirmed')->where('confirmed_by_user', 0)->count();
        // $confirmedCashout = Cashout::where('user_id', $request->id)->where('status', 'confirmed')->get();

        // return response()->json([
        //     "pendingCashoutCount" => $pendingCashoutCount, "pendingCashout" => $pendingCashout,
        //     "completedCashoutCount" => $completedCashoutCount, "completedCashout" => $completedCashout,
        //     "confirmedCashoutCount" => $confirmedCashoutCount, "confirmedCashout" => $confirmedCashout
        // ]);
    }

    public function userCashout(Request $request)
    {
        $user = User::with(['user_account'])->findOrFail(Auth::id());
        return Cashout::where('user_id',  $user->id)->where('confirmed_by_user', 0)->get();
    }

    public function getTotalCashoutsCount(Request $request)
    {
        $pendingCashoutCount = Cashout::where('status', 'pending')->count();
        $completedCashoutCount = Cashout::where('status', 'completed')->count();
        $allConfirmedCashoutCount = Cashout::where('status', 'confirmed')->count();
        $envoyOnlyConfirmedCashoutCount = Cashout::where('status', 'confirmed')->where('confirmed_by_envoy', 1)->where('confirmed_by_user', 0)->count();

        return [
            "pendingCashoutCount" => $pendingCashoutCount, "completedCashoutCount" => $completedCashoutCount,
            "allConfirmedCashoutCount" => $allConfirmedCashoutCount, "envoyOnlyConfirmedCashoutCount" => $envoyOnlyConfirmedCashoutCount
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cashout  $cashout
     * @return \Illuminate\Http\Response
     */
    public function show(Cashout $cashout)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cashout  $cashout
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cashout $cashout)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cashout  $cashout
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cashout $cashout)
    {
        //
    }
}