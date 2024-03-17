<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Rate;
use App\Models\User;
use App\Models\Client;
use App\Models\Account;
use App\Models\Cashout;
use App\Models\Provider;
use App\Models\SendMoney;
use App\Models\UserVisit;
use App\Models\Transaction;
use App\Models\UserAccount;
use App\Models\AgentDeposit;
use App\Rules\ValideAccount;
use Illuminate\Http\Request;
use App\Models\SystemAccount;
use App\Models\AgentInvestment;
use App\Models\authUserAccount;
use App\Events\TransactionEvent;
use App\Models\AgentDebtDeposit;
use App\Models\SystemBankAccount;
use App\Rules\ValideAccountLimit;
use App\Models\TransactionActivit;
use App\Models\TransactionComment;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Events\NewTransactionEvent;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Notifications\TransactionDone;
use App\Http\Resources\AccountResource;
use App\Models\ReplenishmentRequirement;
use Spatie\Permission\Models\Permission;
use League\CommonMark\Reference\Reference;
use Illuminate\Support\Facades\Notification;
use App\Http\Requests\TransactionFormRequest;
use App\Http\Resources\ApprovalCompletedResource;
use App\Http\Requests\ApprovalCompletedFormRequest;
use App\Services\Transactions\TransactionSortService;
use App\Services\Transactions\TransactionCancelService;
use App\Services\Transactions\TransactionReviewService;
use App\Services\Transactions\TransactionDetailsService;
use App\Services\Transactions\TransactionDisplayService;
use App\Services\Transactions\TransactionCompletionService;
use App\Services\Transactions\TransactionCheckInvervalService;
use App\Services\Transactions\TransactionApproveOrDisapproveService;
use App\Services\Transactions\TransactionStoreService;

class TransactionController extends Controller
{
    protected $transactionDisplayService;
    protected $transactionCompletionService;
    protected $transactionApproveOrDisapproveService;
    protected $transactionCheckInvervalService;
    protected $transactionCancelService;
    protected $transactionReviewService;
    protected $transactionSortService;
    protected $transactionDetailsService;
    protected $transactionStoreService;
    private $currMonth;
    private $currYear;

    public function __construct(TransactionDisplayService $transactionDisplayService,
                                TransactionCompletionService $transactionCompletionService,
                                TransactionApproveOrDisapproveService $transactionApproveOrDisapproveService,
                                TransactionCheckInvervalService $transactionCheckInvervalService,
                                TransactionCancelService $transactionCancelService,
                                TransactionReviewService $transactionReviewService,
                                TransactionSortService $transactionSortService,
                                TransactionDetailsService $transactionDetailsService,
                                TransactionStoreService $transactionStoreService)
    {
        $this->transactionStoreService = $transactionStoreService;
        $this->transactionDisplayService = $transactionDisplayService;
        $this->transactionCompletionService = $transactionCompletionService;
        $this->transactionApproveOrDisapproveService = $transactionApproveOrDisapproveService;
        $this->transactionCheckInvervalService = $transactionCheckInvervalService;
        $this->transactionCancelService = $transactionCancelService;
        $this->transactionReviewService = $transactionReviewService;
        $this->transactionSortService = $transactionSortService;
        $this->transactionDetailsService = $transactionDetailsService;
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
        $currentMonth = $this->currMonth;
        $currentYear = $this->currYear;

        return $this->transactionDisplayService->getTransactions($currentMonth, $currentYear);
    }

    public function getCompletedTransactions()
    {
        $currentMonth = $this->currMonth;
        $currentYear = $this->currYear;
        $limit = 30;

        if (Auth::user()->hasRole('super-admin') || Auth::user()->hasRole('system-engineer') || Auth::user()->hasRole('admin')) {
            return Transaction::with(['client', 'user', 'transaction_comments'])
                ->join('users as operator', 'transactions.operator_id', '=', 'operator.id')
                ->join('clients as receiver', 'transactions.receiver', '=', 'receiver.id')
                ->select(
                    'transactions.*',
                    'operator.id as operator_id',
                    'operator.code as operator_code',
                    'operator.first_name as operator_first_name',
                    'operator.last_name as operator_last_name',
                    'operator.email as operator_email',
                    'receiver.id as receiver_id',
                    'receiver.name as receiver_name',
                    'receiver.phone as receiver_phone'
                )->whereMonth('transactions.created_at', '=', $currentMonth)
                ->whereYear('transactions.created_at', '=', $currentYear)
                ->where('transactions.status', '=', 'completed')
                ->orderBy('transactions.created_at', 'DESC')
                ->take($limit)
                ->get();
        } 
        if (Auth::user()->hasRole('agent')) {
            return Transaction::with(['client', 'user', 'transaction_comments'])
            ->where('user_id', Auth::id())
            ->leftJoin('users as operator', 'transactions.operator_id', '=', 'operator.id')
            ->join('clients as receiver', 'transactions.receiver', '=', 'receiver.id')
            ->select(
                'transactions.*',
                'operator.id as operator_id',
                'operator.code as operator_code',
                'operator.first_name as operator_first_name',
                'operator.last_name as operator_last_name',
                'operator.email as operator_email',
                'receiver.id as receiver_id',
                'receiver.name as receiver_name',
                'receiver.phone as receiver_phone',
            )->whereMonth('transactions.created_at', '=', $currentMonth)
            ->whereYear('transactions.created_at', '=', $currentYear)
            ->where('transactions.status', '=', 'completed')
            ->orderBy('transactions.created_at', 'DESC')
            ->take($limit)
            ->get();
        }
        if (Auth::user()->hasRole('operator')) {
            return Transaction::with(['client', 'user', 'transaction_comments'])
                ->where('operator_id', Auth::id())
                ->join('users as operator', 'transactions.operator_id', '=', 'operator.id')
                ->join('clients as receiver', 'transactions.receiver', '=', 'receiver.id')
                ->select(
                    'transactions.*',
                    'operator.id as operator_id',
                    'operator.code as operator_code',
                    'operator.first_name as operator_first_name',
                    'operator.last_name as operator_last_name',
                    'operator.email as operator_email',
                    'receiver.id as receiver_id',
                    'receiver.name as receiver_name',
                    'receiver.phone as receiver_phone',
                )->whereMonth('transactions.created_at', '=', $currentMonth)
                ->whereYear('transactions.created_at', '=', $currentYear)
                ->where('transactions.status', '=', 'completed')
                ->orderBy('transactions.created_at', 'DESC')
                ->take($limit)
                ->get();
        } 
    }

    public function transactionSearch(Request $request)
    {
        // Start building the base query
        $query = Transaction::with(['client', 'user', 'transaction_comments'])
        ->join('users as operator', 'transactions.operator_id', '=', 'operator.id')
        ->join('clients as receiver', 'transactions.receiver', '=', 'receiver.id')
        ->select(
            'transactions.*',
            'operator.id as operator_id',
            'operator.code as operator_code',
            'operator.first_name as operator_first_name',
            'operator.last_name as operator_last_name',
            'operator.email as operator_email',
            'receiver.id as receiver_id',
            'receiver.name as receiver_name',
            'receiver.phone as receiver_phone',
        );

        // Apply search filters
        if ($request->filled('q_id')) {
            $query->where('transactions.id', 'LIKE', '%' . strtolower(str_replace(' ', '', $request->q_id)) . '%');
        } elseif ($request->filled('q_name')) {
            $query->whereRaw("LOWER(REPLACE(receiver.name, ' ', '')) LIKE ?", ['%' . strtolower(str_replace(' ', '', $request->q_name)) . '%']);
        } elseif ($request->filled('q_phone')) {
            $query->whereRaw("LOWER(REPLACE(receiver.phone, ' ', '')) LIKE ?", ['%' . strtolower(str_replace(' ', '', $request->q_phone)) . '%']);
           // $query->where('receiver.phone', 'LIKE', '%' . strtolower(str_replace(' ', '', $request->q_phone)) . '%');
        }

        // Apply status filter
        if ($request->status === 'completed') {
            $query->where('status', 'completed');
        } else {
            $query->where('status', '!=', 'completed');
        }

        // Apply user role restrictions
        if (Auth::user()->hasRole('operator')) {
            $query->where('operator_id', Auth::id());
        } elseif(Auth::user()->hasRole('agent')){
            $query->where('user_id', Auth::id());
        }

        // Perform pagination
        $results = $request->status == 'completed' 
        ? $query->orderByDesc('completed_date')->take(5)->get()
        : $query->orderByDesc('updated_at')->take(5)->paginate(5);

        return $results;
    }

    // public function transactionSearch(Request $request)
    // {
    //     $query = Transaction::with(['client', 'user', 'transaction_comments'])
    //         ->where(function ($query) use ($request) {
    //             if ($request->has('q_id')) {
    //                 $query->whereRaw("REPLACE(id, ' ', '') LIKE '%" . str_replace(' ', '', $request->q_id) . "%'");
    //             } elseif ($request->has('q_name')) {
    //                 $query->whereHas('client', function ($query) use ($request) {
    //                     $query->whereRaw("REPLACE(name, ' ', '') LIKE '%" . str_replace(' ', '', $request->q_name) . "%'");
    //                 });
    //             } elseif ($request->has('q_phone')) {
    //                 $query->whereHas('client', function ($query) use ($request) {
    //                     $query->whereRaw("REPLACE(phone, ' ', '') LIKE '%" . str_replace(' ', '', $request->q_phone) . "%'");
    //                 });
    //             }
    //         });

    //     // Check if status parameter is set to 'completed'
    //     if ($request->status === 'completed') {
    //         $query->where('status', 'completed');
    //     } else {
    //         $query->where('status', '!=', 'completed');
    //     }

    //     // Handle user roles and pagination
    //     switch (true) {
    //         case Auth::user()->hasRole('super-admin'):
    //             $query->orderBy('transactions.created_at', 'DESC')->take(20);
    //             break;
    //         case Auth::user()->hasRole('admin'):
    //             $query->orderBy('transactions.created_at', 'DESC')->paginate(50);
    //             break;
    //         case Auth::user()->hasRole('operator'):
    //             $query->where('operator_id', Auth::id())->orderBy('transactions.created_at', 'DESC')->paginate(50);
    //             break;
    //         default:
    //             $query->where('user_id', Auth::id())->orderBy('transactions.created_at', 'DESC')->paginate(50);
    //             break;
    //     }

    //     return $query->get();
    // }

    public function transactionCancel(Request $request)
    {
        return $this->transactionCancelService->cancelTransaction($request->transaction_id);
    }

    public function setTransactionView(Request $request)
    {
       $transaction = Transaction::findOrFail($request->transaction_id);
       $transaction->update(['viewed' => true]);
       return response()->json($transaction);
    }

    public function setMonthAndYear(Request $request)
    {
        $month = $request->selected_month;
        $year = $request->selected_year;

        $this->currMonth = $month;
        $this->currYear = $year;

        return $this->index();
    }

    public function generatePdf(Request $request)
    {
        $today = Carbon::today();
        $seven_days = Carbon::now()->subDays(7); // Calculate the start date (7 days ago)
        $last_month = Carbon::now()->subMonth();
        $currentYear = Carbon::now()->year; // Get the current year
        $currentMonth = Carbon::now()->month; // Get the current month

        if ($request->pdf_export_option == 'today') {
            return Transaction::with(['client', 'user', 'transaction_comments'])
                ->join('users as operator', 'transactions.operator_id', '=', 'operator.id')
                ->join('clients as receiver', 'transactions.receiver', '=', 'receiver.id')
                ->select(
                    'transactions.*',
                    'operator.id as operator_id',
                    'operator.code as operator_code',
                    'operator.first_name as operator_first_name',
                    'operator.last_name as operator_last_name',
                    'operator.email as operator_email',
                    'receiver.id as receiver_id',
                    'receiver.name as receiver_name',
                    'receiver.phone as receiver_phone',
                )
                ->orderBy('transactions.created_at', 'DESC')
                ->whereDate('transactions.created_at', $today)
                ->get();
        }
        if ($request->pdf_export_option == 'seven_days') {
            return Transaction::with(['client', 'user', 'transaction_comments'])
                ->join('users as operator', 'transactions.operator_id', '=', 'operator.id')
                ->join('clients as receiver', 'transactions.receiver', '=', 'receiver.id')
                ->select(
                    'transactions.*',
                    'operator.id as operator_id',
                    'operator.code as operator_code',
                    'operator.first_name as operator_first_name',
                    'operator.last_name as operator_last_name',
                    'operator.email as operator_email',
                    'receiver.id as receiver_id',
                    'receiver.name as receiver_name',
                    'receiver.phone as receiver_phone',
                )
                ->orderBy('transactions.created_at', 'DESC')
                ->whereBetween('transactions.created_at', [$seven_days, $today])
                ->get();
        }
        if ($request->pdf_export_option == 'this_month') {
            return Transaction::with(['client', 'user', 'transaction_comments'])
                ->join('users as operator', 'transactions.operator_id', '=', 'operator.id')
                ->join('clients as receiver', 'transactions.receiver', '=', 'receiver.id')
                ->select(
                    'transactions.*',
                    'operator.id as operator_id',
                    'operator.code as operator_code',
                    'operator.first_name as operator_first_name',
                    'operator.last_name as operator_last_name',
                    'operator.email as operator_email',
                    'receiver.id as receiver_id',
                    'receiver.name as receiver_name',
                    'receiver.phone as receiver_phone',
                )
                ->orderBy('transactions.created_at', 'DESC')
                ->whereYear('transactions.created_at', $currentYear)
                ->whereMonth('transactions.created_at', $currentMonth)
                ->get();
        }
        if ($request->pdf_export_option == 'last_month') {
            return Transaction::with(['client', 'user', 'transaction_comments'])
                ->join('users as operator', 'transactions.operator_id', '=', 'operator.id')
                ->join('clients as receiver', 'transactions.receiver', '=', 'receiver.id')
                ->select(
                    'transactions.*',
                    'operator.id as operator_id',
                    'operator.code as operator_code',
                    'operator.first_name as operator_first_name',
                    'operator.last_name as operator_last_name',
                    'operator.email as operator_email',
                    'receiver.id as receiver_id',
                    'receiver.name as receiver_name',
                    'receiver.phone as receiver_phone',
                )
                ->orderBy('transactions.created_at', 'DESC')
                ->whereBetween('transactions.created_at', [$last_month, $today])
                ->get();
        }
        if ($request->pdf_export_option == 'all') {
            return Transaction::with(['client', 'user', 'transaction_comments'])
                ->join('users as operator', 'transactions.operator_id', '=', 'operator.id')
                ->join('clients as receiver', 'transactions.receiver', '=', 'receiver.id')
                ->select(
                    'transactions.*',
                    'operator.id as operator_id',
                    'operator.code as operator_code',
                    'operator.first_name as operator_first_name',
                    'operator.last_name as operator_last_name',
                    'operator.email as operator_email',
                    'receiver.id as receiver_id',
                    'receiver.name as receiver_name',
                    'receiver.phone as receiver_phone',
                )
                ->orderBy('transactions.created_at', 'DESC')
                ->get();
        }
    }

    public function dailyRateUpdate(Request $request)
    {
        // DB::table('rate')->update(['daily_rate' => $request->dailyrate]);

        // return DB::table('rate')->get('daily_rate');
    }

    public function getTotalTransaction(Request $request)
    {
        $currentMonth = $request->selected_month;
        $currentYear = $request->selected_year;
        $previousMonth = ($request->selected_month == 1) ? 1 : ($request->selected_month - 1);

        // ------Percentages calcul -------
        // Casgout percentage
        $investmentPercentageForSeletedMonth = AgentInvestment::where('status', 'confirmed')
            ->whereMonth('created_at', '=', $currentMonth)
            ->whereYear('created_at', '=', $currentYear)
            ->sum('amount');
        $investmentPercentagPreviousMonth = AgentInvestment::where('status', 'confirmed')
            ->whereMonth('created_at', '=', $previousMonth)
            ->whereYear('created_at', '=', $currentYear)
            ->sum('amount');
        //End Cashout percentage

        // Investment percentage
        $cashoutPercentageForSeletedMonth = Cashout::where('status', 'confirmed')
            ->whereMonth('created_at', '=', $currentMonth)
            ->whereYear('created_at', '=', $currentYear)
            ->sum('amount');
        $cashoutPercentagPreviousMonth = Cashout::where('status', 'confirmed')
            ->whereMonth('created_at', '=', $previousMonth)
            ->whereYear('created_at', '=', $currentYear)
            ->sum('amount');
        //End Investment percentage

        // Deposit percentage
        $depositPercentageForSeletedMonth = AgentDebtDeposit::where('status', 'confirmed')
            ->whereMonth('created_at', '=', $currentMonth)
            ->whereYear('created_at', '=', $currentYear)
            ->sum('amount');
        $depositPercentagPreviousMonth = AgentDebtDeposit::where('status', 'confirmed')
            ->whereMonth('created_at', '=', $previousMonth)
            ->whereYear('created_at', '=', $currentYear)
            ->sum('amount');
        //End deposit percentage
        // -----End Percentages calcul------

        return [
            'totalTransaction' => Transaction::count(),
            'totalMoncash' => Transaction::where('type', 'moncash')->where('status', 'completed')
                ->whereMonth('created_at', '=', $currentMonth)
                ->whereYear('created_at', '=', $currentYear)
                ->count(),
            'totalNatcash' => Transaction::where('type', 'natcash')->where('status', 'completed')
                ->whereMonth('created_at', '=', $currentMonth)
                ->whereYear('created_at', '=', $currentYear)
                ->count(),
            'totalLocal' => Transaction::where('type', 'local')->where('status', 'completed')
                ->whereMonth('created_at', '=', $currentMonth)
                ->whereYear('created_at', '=', $currentYear)
                ->count(),
            'totalClient' => Client::whereMonth('created_at', '=', $currentMonth)
                ->whereYear('created_at', '=', $currentYear)
                ->count(),
            'totalCompletedTransactionSum' => Transaction::where('status', 'completed')
                ->whereMonth('created_at', '=', $currentMonth)
                ->whereYear('created_at', '=', $currentYear)
                ->sum('receiver_amount'),
            'totalPendingTransactionSum' => Transaction::where('status', 'pending')
                ->whereMonth('created_at', '=', $currentMonth)
                ->whereYear('created_at', '=', $currentYear)
                ->sum('receiver_amount'),
            'totalCashout' => Cashout::where('status', 'confirmed')
                ->whereMonth('updated_at', '=', $currentMonth)
                ->whereYear('updated_at', '=', $currentYear)
                ->sum('amount'),
            'totalRevenue' => SystemAccount::sum('revenues'),
            'systemFunds' => SystemAccount::sum('funds'),
            'systemBankAccountCount' => SystemBankAccount::count(),
            'totalUserBalance' => UserAccount::sum('profits'),
            'totalUserDept' => UserAccount::sum('depts'),
            'totalProviderClaim' => Provider::sum('claim'),
            //'topAgents' => User::with(['roles', 'transactions' => function ($query) {$query->where('status', 'completed')->orderBy('sender_amount', 'desc')->count();}])->whereRelation('transactions','status', '=', 'completed')->whereRelation('roles','name', '=', 'agent')->limit(3)->get(),
            //'topAgents' => User::withCount('transactions')->with(['transactions' => function ($query) {$query->orderBy('sender_amount', 'desc')->count();}])->take(3)->get(),
            // 'topAgents' => Role::where('name', 'agent')->first()->users()
            //                 ->whereHas('transactions', function ($query) use ($currentMonth, $currentYear) {
            //                     $query->where('status', 'completed')
            //                     ->whereMonth('created_at', $currentMonth)
            //                     ->whereYear('created_at', $currentYear);
            //                 })->withCount('transactions')->orderByDesc('transactions_count')->limit(3)->get(),
            'topOperators' => DB::table('users')
                ->select('users.*', DB::raw('COUNT(transactions.operator_id) as transaction_count'))
                ->leftJoin('transactions', 'users.id', '=', 'transactions.operator_id')
                ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->where('roles.name', '=', 'operator')
                ->where('transactions.status', '=', 'completed')
                ->whereMonth('transactions.created_at', '=', $currentMonth)
                ->whereYear('transactions.created_at', '=', $currentYear)
                ->groupBy('users.id', 'users.first_name')
                ->orderByDesc('transaction_count')
                ->take(3)
                ->get(),
            'topAgents' => DB::table('users')
                ->select('users.*', DB::raw('COUNT(transactions.user_id) as transaction_count'))
                ->leftJoin('transactions', 'users.id', '=', 'transactions.user_id')
                ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->where('roles.name', '=', 'agent')
                ->where('transactions.status', '=', 'completed')
                ->whereMonth('transactions.created_at', '=', $currentMonth)
                ->whereYear('transactions.created_at', '=', $currentYear)
                ->groupBy('users.id', 'users.first_name')
                ->orderByDesc('transaction_count')
                ->take(3)
                ->get(),
            'topClients' => DB::table('clients')
                ->select('clients.*', DB::raw('COUNT(transactions.client_id) as transaction_count'))
                ->leftJoin('transactions', 'clients.id', '=', 'transactions.client_id')
                ->where('transactions.status', '=', 'completed')
                ->whereMonth('transactions.created_at', '=', $currentMonth)
                ->whereYear('transactions.created_at', '=', $currentYear)
                ->groupBy('clients.id', 'clients.name')
                ->orderByDesc('transaction_count')
                ->take(3)
                ->get(),
            'totalCashout' => Cashout::where('status', 'confirmed')
                ->whereMonth('updated_at', '=', $currentMonth)
                ->whereYear('updated_at', '=', $currentYear)
                ->sum('amount'),
            // 'totalDeposit' => AgentDebtDeposit::whereMonth('created_at', '=', $currentMonth)
            //     ->whereYear('created_at', '=', $currentYear)
            //     ->sum('amount'),
            // 'totalInvestment' => AgentInvestment::whereMonth('created_at', '=', $currentMonth)
            //     ->whereYear('created_at', '=', $currentYear)
            //     ->sum('amount'),
            'totalDeposit' => AgentDeposit::whereMonth('created_at', '=', $currentMonth)
                ->where('status', 'confirmed')
                ->whereYear('created_at', '=', $currentYear)
                ->sum('amount'),
            'totalInvestment' => UserAccount::sum('investments'),
            'userVisitOnPhone' => UserVisit::where('device_type', 'phone')->count(),
            'userVisitOnDesktop' => UserVisit::where('device_type', 'desktop')->count(),
            // 'topClients' => Client::whereHas('transactions', function ($query) use ($currentMonth, $currentYear) {
            //                     $query->where('status', 'completed')
            //                     ->whereMonth('created_at', $currentMonth)
            //                     ->whereYear('created_at', $currentYear);
            //                 })->withCount('transactions')->orderByDesc('transactions_count')->limit(3)->get(),
            'cashoutPercentageForSeletedMonth' => $cashoutPercentageForSeletedMonth,
            'cashoutPercentagPreviousMonth' => $cashoutPercentagPreviousMonth,
            'cashoutPercentage' => $cashoutPercentage = ($cashoutPercentagPreviousMonth == 0) ? ($cashoutPercentageForSeletedMonth * 0) : (($cashoutPercentageForSeletedMonth - $cashoutPercentagPreviousMonth) / $cashoutPercentagPreviousMonth * 100),

            'investmentPercentageForSeletedMonth' => $investmentPercentageForSeletedMonth,
            'investmentPercentagPreviousMonth' => $investmentPercentagPreviousMonth,
            'investmentPercentage' => $investmentPercentage = ($investmentPercentagPreviousMonth == 0) ? ($investmentPercentageForSeletedMonth * 0) : (($investmentPercentageForSeletedMonth - $investmentPercentagPreviousMonth) / $investmentPercentagPreviousMonth * 100),

            'depositPercentageForSeletedMonth' => $depositPercentageForSeletedMonth,
            'depositPercentagPreviousMonth' => $depositPercentagPreviousMonth,
            'depositPercentage' => $depositPercentage = ($depositPercentagPreviousMonth == 0) ? ($depositPercentageForSeletedMonth * 0) : (($depositPercentageForSeletedMonth - $depositPercentagPreviousMonth) / $depositPercentagPreviousMonth * 100),
        ];
    }

    public function gettotalUsertransaction(Request $request)
    {
        //$currentMonth = Carbon::now()->month;
        // $currentYear = Carbon::now()->year;
        $currentMonth = $request->selected_month;
        $currentYear = $request->selected_year;
        $previousMonth = ($request->selected_month == 1) ? 1 : ($request->selected_month - 1);

        // ------Percentages calcul -------
        // Casgout percentage
        $investmentPercentageForSeletedMonth = AgentInvestment::where('user_id', Auth::id())
            ->where('status', 'confirmed')
            ->whereMonth('created_at', '=', $currentMonth)
            ->whereYear('created_at', '=', $currentYear)
            ->sum('amount');
        $investmentPercentagPreviousMonth = AgentInvestment::where('user_id', Auth::id())
            ->where('status', 'confirmed')
            ->whereMonth('created_at', '=', $previousMonth)
            ->whereYear('created_at', '=', $currentYear)
            ->sum('amount');
        //End Cashout percentage

        // Investment percentage
        $cashoutPercentageForSeletedMonth = Cashout::where('user_id', Auth::id())
            ->where('status', 'confirmed')
            ->whereMonth('created_at', '=', $currentMonth)
            ->whereYear('created_at', '=', $currentYear)
            ->sum('amount');
        $cashoutPercentagPreviousMonth = Cashout::where('user_id', Auth::id())
            ->where('status', 'confirmed')
            ->whereMonth('created_at', '=', $previousMonth)
            ->whereYear('created_at', '=', $currentYear)
            ->sum('amount');
        //End Investment percentage

        // Deposit percentage
        $depositPercentageForSeletedMonth = AgentDebtDeposit::where('user_id', Auth::id())
            ->where('status', 'confirmed')
            ->whereMonth('created_at', '=', $currentMonth)
            ->whereYear('created_at', '=', $currentYear)
            ->sum('amount');
        $depositPercentagPreviousMonth = AgentDebtDeposit::where('user_id', Auth::id())
            ->where('status', 'confirmed')
            ->whereMonth('created_at', '=', $previousMonth)
            ->whereYear('created_at', '=', $currentYear)
            ->sum('amount');
        //End deposit percentage
        // -----End Percentages calcul------

        if (Auth::user()->hasRole('agent')) {
            return [
                'totalTransaction' => Transaction::where('user_id', Auth::id())
                    ->whereMonth('created_at', '=', $currentMonth)
                    ->whereYear('created_at', '=', $currentYear)
                    ->count(),
                'totalMoncash' => Transaction::where('user_id', Auth::id())->where('type', 'moncash')->where('status', 'completed')
                    ->whereMonth('created_at', '=', $currentMonth)
                    ->whereYear('created_at', '=', $currentYear)
                    ->count(),
                'totalNatcash' => Transaction::where('user_id', Auth::id())->where('type', 'natcash')->where('status', 'completed')
                    ->whereMonth('created_at', '=', $currentMonth)
                    ->whereYear('created_at', '=', $currentYear)
                    ->count(),
                'totalLocal' => Transaction::where('user_id', Auth::id())->where('type', 'local')->where('status', 'completed')
                    ->whereMonth('created_at', '=', $currentMonth)
                    ->whereYear('created_at', '=', $currentYear)
                    ->count(),
                'totalCompletedTransactionSum' => Transaction::where('user_id', Auth::id())
                    ->whereMonth('created_at', '=', $currentMonth)
                    ->whereYear('created_at', '=', $currentYear)
                    ->sum('sender_amount'),
                'totalPendingTransactionSum' => Transaction::where('user_id', Auth::id())
                    ->whereMonth('created_at', '=', $currentMonth)
                    ->whereYear('created_at', '=', $currentYear)
                    ->sum('sender_amount'),
                'totalUserBalance' => UserAccount::sum('profits'),

                'sumTransfertTotalTransaction' => Transaction::where('user_id', Auth::id())
                    ->where('operation', 'transfert_si')->where('status', '!=', 'disapproved')
                    ->whereMonth('created_at', '=', $currentMonth)
                    ->whereYear('created_at', '=', $currentYear)
                    ->sum('sender_amount'),

                'sumReceptionTotalTransaction' => Transaction::where('user_id', Auth::id())
                    ->where('operation', 'reception_si')->where('status', '!=', 'disapproved')
                    ->whereMonth('created_at', '=', $currentMonth)
                    ->whereYear('created_at', '=', $currentYear)
                    ->sum('receiver_amount'),

                'sumCompletedTransfert' => Transaction::where('user_id', Auth::id())
                    ->where('operation', 'transfert_si')->where('status', 'completed')
                    ->whereMonth('created_at', '=', $currentMonth)
                    ->whereYear('created_at', '=', $currentYear)
                    ->sum('sender_amount'),
                'sumCompletedMoncashTransfert' => Transaction::where('user_id', Auth::id())
                    ->where('type', 'moncash')->where('operation', 'transfert_si')->where('status', 'completed')
                    ->whereMonth('created_at', '=', $currentMonth)
                    ->whereYear('created_at', '=', $currentYear)
                    ->sum('sender_amount'),
                'sumCompletedNatcashTransfert' => Transaction::where('user_id', Auth::id())
                    ->where('type', 'natcash')->where('operation', 'transfert_si')->where('status', 'completed')
                    ->whereMonth('created_at', '=', $currentMonth)
                    ->whereYear('created_at', '=', $currentYear)
                    ->sum('sender_amount'),

                'sumApprovedTransfert' => Transaction::where('user_id', Auth::id())
                    ->where('operation', 'transfert_si')->where('status', 'approved')
                    ->whereMonth('created_at', '=', $currentMonth)
                    ->whereYear('created_at', '=', $currentYear)
                    ->sum('sender_amount'),
                'sumApprovedMoncashTransfert' => Transaction::where('user_id', Auth::id())
                    ->where('type', 'moncash')->where('operation', 'transfert_si')->where('status', 'approved')
                    ->whereMonth('created_at', '=', $currentMonth)
                    ->whereYear('created_at', '=', $currentYear)
                    ->sum('sender_amount'),
                'sumApprovedNatcashTransfert' => Transaction::where('user_id', Auth::id())
                    ->where('type', 'natcash')->where('operation', 'transfert_si')->where('status', 'approved')
                    ->whereMonth('created_at', '=', $currentMonth)
                    ->whereYear('created_at', '=', $currentYear)
                    ->sum('sender_amount'),

                'sumPendingTransfert' => Transaction::where('user_id', Auth::id())
                    ->where('operation', 'transfert_si')->where('status', 'pending')
                    ->whereMonth('created_at', '=', $currentMonth)
                    ->whereYear('created_at', '=', $currentYear)
                    ->sum('sender_amount'),
                'sumPendingMoncashTransfert' => Transaction::where('user_id', Auth::id())
                    ->where('type', 'moncash')->where('operation', 'transfert_si')->where('status', 'pending')
                    ->whereMonth('created_at', '=', $currentMonth)
                    ->whereYear('created_at', '=', $currentYear)
                    ->sum('sender_amount'),
                'sumPendingNatcashTransfert' => Transaction::where('user_id', Auth::id())
                    ->where('type', 'natcash')->where('operation', 'transfert_si')->where('status', 'pending')
                    ->whereMonth('created_at', '=', $currentMonth)
                    ->whereYear('created_at', '=', $currentYear)
                    ->sum('sender_amount'),

                'sumCompletedReception' => Transaction::where('user_id', Auth::id())
                    ->where('operation', 'reception_si')->where('status', 'completed')
                    ->whereMonth('created_at', '=', $currentMonth)
                    ->whereYear('created_at', '=', $currentYear)
                    ->sum('receiver_amount'),
                'sumCompletedMoncashReception' => Transaction::where('user_id', Auth::id())
                    ->where('type', 'moncash')->where('operation', 'reception_si')->where('status', 'completed')
                    ->whereMonth('created_at', '=', $currentMonth)
                    ->whereYear('created_at', '=', $currentYear)
                    ->sum('receiver_amount'),
                'sumCompletedNatcashReception' => Transaction::where('user_id', Auth::id())
                    ->where('type', 'natcash')->where('operation', 'reception_si')->where('status', 'completed')
                    ->whereMonth('created_at', '=', $currentMonth)
                    ->whereYear('created_at', '=', $currentYear)
                    ->sum('receiver_amount'),

                'sumApprovedReception' => Transaction::where('user_id', Auth::id())
                    ->where('operation', 'reception_si')->where('status', 'approved')
                    ->whereMonth('created_at', '=', $currentMonth)
                    ->whereYear('created_at', '=', $currentYear)
                    ->sum('receiver_amount'),
                'sumApprovedMoncashReception' => Transaction::where('user_id', Auth::id())
                    ->where('type', 'moncash')->where('operation', 'reception_si')->where('status', 'approved')
                    ->whereMonth('created_at', '=', $currentMonth)
                    ->whereYear('created_at', '=', $currentYear)
                    ->sum('receiver_amount'),
                'sumApprovedNatcashReception' => Transaction::where('user_id', Auth::id())
                    ->where('type', 'natcash')->where('operation', 'reception_si')->where('status', 'approved')
                    ->whereMonth('created_at', '=', $currentMonth)
                    ->whereYear('created_at', '=', $currentYear)
                    ->sum('receiver_amount'),

                'sumPendingReception' => Transaction::where('user_id', Auth::id())
                    ->where('operation', 'reception_si')->where('status', 'pending')
                    ->whereMonth('created_at', '=', $currentMonth)
                    ->whereYear('created_at', '=', $currentYear)
                    ->sum('receiver_amount'),
                'sumPendingMoncashReception' => Transaction::where('user_id', Auth::id())
                    ->where('type', 'moncash')->where('operation', 'reception_si')->where('status', 'pending')
                    ->whereMonth('created_at', '=', $currentMonth)
                    ->whereYear('created_at', '=', $currentYear)
                    ->sum('receiver_amount'),
                'sumPendingNatcashReception' => Transaction::where('user_id', Auth::id())
                    ->where('type', 'natcash')->where('operation', 'reception_si')->where('status', 'pending')
                    ->whereMonth('created_at', '=', $currentMonth)
                    ->whereYear('created_at', '=', $currentYear)
                    ->sum('receiver_amount'),

                'totalCashout' => Cashout::where('user_id', Auth::id())
                    ->where('status', 'confirmed')
                    ->whereMonth('updated_at', '=', $currentMonth)
                    ->whereYear('updated_at', '=', $currentYear)
                    ->sum('amount'),

                // 'totalDeposit' => AgentDebtDeposit::where('user_id', Auth::id())
                //     ->whereMonth('updated_at', '=', $currentMonth)
                //     ->whereYear('updated_at', '=', $currentYear)
                //     ->sum('amount'),

                // 'totalInvestment' => AgentInvestment::where('user_id', Auth::id())
                //     ->whereMonth('updated_at', '=', $currentMonth)
                //     ->whereYear('updated_at', '=', $currentYear)
                //     ->sum('amount'),

                'totalDeposit' => AgentDeposit::where('user_id', Auth::id())
                    ->where('status', 'confirmed')
                    ->whereMonth('created_at', '=', $currentMonth)
                    ->whereYear('created_at', '=', $currentYear)
                    ->sum('amount'),
                'totalInvestment' => UserAccount::where('user_id', Auth::id())->sum('investments'),

                'cashoutPercentageForSeletedMonth' => $cashoutPercentageForSeletedMonth,
                'cashoutPercentagPreviousMonth' => $cashoutPercentagPreviousMonth,
                'cashoutPercentage' => ($cashoutPercentagPreviousMonth == 0) ? ($cashoutPercentageForSeletedMonth * 0) : (($cashoutPercentageForSeletedMonth - $cashoutPercentagPreviousMonth) / $cashoutPercentagPreviousMonth * 100),

                'investmentPercentageForSeletedMonth' => $investmentPercentageForSeletedMonth,
                'investmentPercentagPreviousMonth' => $investmentPercentagPreviousMonth,
                'investmentPercentage' => ($investmentPercentagPreviousMonth == 0) ? ($investmentPercentageForSeletedMonth * 0) : (($investmentPercentageForSeletedMonth - $investmentPercentagPreviousMonth) / $investmentPercentagPreviousMonth * 100),

                'depositPercentageForSeletedMonth' => $depositPercentageForSeletedMonth,
                'depositPercentagPreviousMonth' => $depositPercentagPreviousMonth,
                'depositPercentage' => ($depositPercentagPreviousMonth == 0) ? ($depositPercentageForSeletedMonth * 0) : (($depositPercentageForSeletedMonth - $depositPercentagPreviousMonth) / $depositPercentagPreviousMonth * 100),
            ];
        }
        if (Auth::user()->hasRole('operator')) {
            return [
                'totalTransaction' => Transaction::where('operator_id', Auth::id())
                    ->whereMonth('completed_date', '=', $currentMonth)
                    ->whereYear('completed_date', '=', $currentYear)
                    ->count(),
                'totalMoncash' => Transaction::where('operator_id', Auth::id())->where('type', 'moncash')->where('status', 'completed')
                    ->whereMonth('completed_date', '=', $currentMonth)
                    ->whereYear('completed_date', '=', $currentYear)
                    ->count(),
                'totalNatcash' => Transaction::where('operator_id', Auth::id())->where('type', 'natcash')->where('status', 'completed')
                    ->whereMonth('completed_date', '=', $currentMonth)
                    ->whereYear('completed_date', '=', $currentYear)
                    ->count(),
                'totalLocal' => Transaction::where('operator_id', Auth::id())->where('type', 'local')->where('status', 'completed')
                    ->whereMonth('completed_date', '=', $currentMonth)
                    ->whereYear('completed_date', '=', $currentYear)
                    ->count(),
                'totalCompletedTransactionSum' => Transaction::where('operator_id', Auth::id())
                    ->whereMonth('completed_date', '=', $currentMonth)
                    ->whereYear('completed_date', '=', $currentYear)
                    ->sum('sender_amount'),
                'totalPendingTransactionSum' => Transaction::where('operator_id', Auth::id())
                    ->whereMonth('completed_date', '=', $currentMonth)
                    ->whereYear('completed_date', '=', $currentYear)
                    ->sum('sender_amount'),
                'totalUserBalance' => UserAccount::sum('profits'),
            ];
        }
    }

    public function getAccountsTransac(Request $request)
    {
        $accounts = User::with(['operator_accounts' => function ($query) use ($request) {
            $query->where('type', $request->type);
        }])->find(Auth::id());          
        return ApprovalCompletedResource::collection($accounts->operator_accounts);
    }

    public function notification()
    {
        return [
            'read' => Auth::user()->readNotifications->count(),
            'unread' => Auth::user()->unreadNotifications->count(),
        ];
    }

    public function getTransactionInfo(Request $request)
    {
        $user = User::where('id', $request->user_id)->get();
        $client = Client::where('id', $request->client_id)->get();
        $sender = Client::where('id', $request->sender_id)->get();
        $receiver = Client::where('id', $request->receiver_id)->get();
        $approver = User::where('id', $request->approver_id)->get();
        $initiator_comment = TransactionComment::with(['user'])->where('transaction_id', $request->transaction_id)->where('timing', 'pending')->get();
        $approver_comment = TransactionComment::with(['user'])->where('transaction_id', $request->transaction_id)->where('timing', 'approved')->get();
        $disapprover_comment = TransactionComment::with(['user'])->where('transaction_id', $request->transaction_id)->where('timing', 'disapproved')->get();
        $concluder = User::where('id', $request->concluder_id)->get();
        $concluder_comment = TransactionComment::with(['user'])->where('transaction_id', $request->transaction_id)->where('timing', 'completed')->get();
        $account = Account::where('id', $request->account_id)->get();

        return response()->json([
            'user' => $user, 'client' => $client, 'sender' => $sender, 'receiver' => $receiver,
            'approver' => $approver, 'approver_comment' => $approver_comment,
            'disapprover_comment' => $disapprover_comment, 'concluder_comment' => $concluder_comment,
            'initiator_comment' => $initiator_comment, 'concluder' => $concluder, 'account' => $account
        ]);
    }

    public function checkIfClientExist(Request $request)
    {
        return Client::where('phone', $request->phone)->get();
    }

    public function transactionSort(Request $request)
    {
        return $this->transactionSortService->sortTransaction($request, $this->currMonth, $this->currYear);
    }

    public function getTransactionDetails(Request $request)
    {
        return $this->transactionDetailsService->transactionDetails($request);
    }

    public function getMonthlyTransactionChart(Request $request)
    {
        $january = Transaction::whereMonth('created_at', Carbon::parse('january')->format('m'))
            ->whereYear('created_at', $request->selected_year)
            ->where('type', 'moncash')
            ->where('status', 'completed')
            ->count();
        $february = Transaction::whereMonth('created_at', Carbon::parse('february')->format('m'))
            ->whereYear('created_at', $request->selected_year)
            ->where('type', 'moncash')
            ->where('status', 'completed')
            ->count();
        $march = Transaction::whereMonth('created_at', Carbon::parse('march')->format('m'))
            ->whereYear('created_at', $request->selected_year)
            ->where('type', 'moncash')
            ->where('status', 'completed')
            ->count();
        $april = Transaction::whereMonth('created_at', Carbon::parse('april')->format('m'))
            ->whereYear('created_at', $request->selected_year)
            ->where('type', 'moncash')
            ->where('status', 'completed')
            ->count();
        $may = Transaction::whereMonth('created_at', Carbon::parse('may')->format('m'))
            ->whereYear('created_at', $request->selected_year)
            ->where('type', 'moncash')
            ->where('status', 'completed')
            ->count();
        $june = Transaction::whereMonth('created_at', Carbon::parse('june')->format('m'))
            ->whereYear('created_at', $request->selected_year)
            ->where('type', 'moncash')
            ->where('status', 'completed')
            ->count();
        $july = Transaction::whereMonth('created_at', Carbon::parse('july')->format('m'))
            ->whereYear('created_at', $request->selected_year)
            ->where('type', 'moncash')
            ->where('status', 'completed')
            ->count();
        $august = Transaction::whereMonth('created_at', Carbon::parse('august')->format('m'))
            ->whereYear('created_at', $request->selected_year)
            ->where('type', 'moncash')
            ->where('status', 'completed')
            ->count();
        $september = Transaction::whereMonth('created_at', Carbon::parse('september')->format('m'))
            ->whereYear('created_at', $request->selected_year)
            ->where('type', 'moncash')
            ->where('status', 'completed')
            ->count();
        $october = Transaction::whereMonth('created_at', Carbon::parse('october')->format('m'))
            ->whereYear('created_at', $request->selected_year)
            ->where('type', 'moncash')
            ->where('status', 'completed')
            ->count();
        $november = Transaction::whereMonth('created_at', Carbon::parse('november')->format('m'))
            ->whereYear('created_at', $request->selected_year)
            ->where('type', 'moncash')
            ->where('status', 'completed')
            ->count();
        $december = Transaction::whereMonth('created_at', Carbon::parse('december')->format('m'))
            ->whereYear('created_at', $request->selected_year)
            ->where('type', 'moncash')
            ->where('status', 'completed')
            ->count();

        $januaryNatcash = Transaction::whereMonth('created_at', Carbon::parse('january')->format('m'))
            ->whereYear('created_at', $request->selected_year)
            ->where('type', 'natcash')
            ->where('status', 'completed')
            ->count();
        $februaryNatcash = Transaction::whereMonth('created_at', Carbon::parse('february')->format('m'))
            ->whereYear('created_at', $request->selected_year)
            ->where('type', 'natcash')
            ->where('status', 'completed')
            ->count();
        $marchNatcash = Transaction::whereMonth('created_at', Carbon::parse('march')->format('m'))
            ->whereYear('created_at', $request->selected_year)
            ->where('type', 'natcash')
            ->where('status', 'completed')
            ->count();
        $aprilNatcash = Transaction::whereMonth('created_at', Carbon::parse('april')->format('m'))
            ->whereYear('created_at', $request->selected_year)
            ->where('type', 'natcash')
            ->where('status', 'completed')
            ->count();
        $mayNatcash = Transaction::whereMonth('created_at', Carbon::parse('may')->format('m'))
            ->whereYear('created_at', $request->selected_year)
            ->where('type', 'natcash')
            ->where('status', 'completed')
            ->count();
        $juneNatcash = Transaction::whereMonth('created_at', Carbon::parse('june')->format('m'))
            ->whereYear('created_at', $request->selected_year)
            ->where('type', 'natcash')
            ->where('status', 'completed')
            ->count();
        $julyNatcash = Transaction::whereMonth('created_at', Carbon::parse('july')->format('m'))
            ->whereYear('created_at', $request->selected_year)
            ->where('type', 'natcash')
            ->where('status', 'completed')
            ->count();
        $augustNatcash = Transaction::whereMonth('created_at', Carbon::parse('august')->format('m'))
            ->whereYear('created_at', $request->selected_year)
            ->where('type', 'natcash')
            ->where('status', 'completed')
            ->count();
        $septemberNatcash = Transaction::whereMonth('created_at', Carbon::parse('september')->format('m'))
            ->whereYear('created_at', $request->selected_year)
            ->where('type', 'natcash')
            ->where('status', 'completed')
            ->count();
        $octoberNatcash = Transaction::whereMonth('created_at', Carbon::parse('october')->format('m'))
            ->whereYear('created_at', $request->selected_year)
            ->where('type', 'natcash')
            ->where('status', 'completed')
            ->count();
        $novemberNatcash = Transaction::whereMonth('created_at', Carbon::parse('november')->format('m'))
            ->whereYear('created_at', $request->selected_year)
            ->where('type', 'natcash')
            ->where('status', 'completed')
            ->count();
        $decemberNatcash = Transaction::whereMonth('created_at', Carbon::parse('december')->format('m'))
            ->whereYear('created_at', $request->selected_year)
            ->where('type', 'natcash')
            ->where('status', 'completed')
            ->count();

        $request->selected_year = date('Y'); // Use the current year, you can change this to any year you want

        $januaryLocal = Transaction::whereMonth('created_at', Carbon::parse('january')->format('m'))
            ->whereYear('created_at', $request->selected_year)
            ->where('type', 'local')
            ->where('status', 'completed')
            ->count();

        $februaryLocal = Transaction::whereMonth('created_at', Carbon::parse('february')->format('m'))
            ->whereYear('created_at', $request->selected_year)
            ->where('type', 'local')
            ->where('status', 'completed')
            ->count();

        $marchLocal = Transaction::whereMonth('created_at', Carbon::parse('march')->format('m'))
            ->whereYear('created_at', $request->selected_year)
            ->where('type', 'local')
            ->where('status', 'completed')
            ->count();

        $aprilLocal = Transaction::whereMonth('created_at', Carbon::parse('april')->format('m'))
            ->whereYear('created_at', $request->selected_year)
            ->where('type', 'local')
            ->where('status', 'completed')
            ->count();

        $mayLocal = Transaction::whereMonth('created_at', Carbon::parse('may')->format('m'))
            ->whereYear('created_at', $request->selected_year)
            ->where('type', 'local')
            ->where('status', 'completed')
            ->count();

        $juneLocal = Transaction::whereMonth('created_at', Carbon::parse('june')->format('m'))
            ->whereYear('created_at', $request->selected_year)
            ->where('type', 'local')
            ->where('status', 'completed')
            ->count();

        $julyLocal = Transaction::whereMonth('created_at', Carbon::parse('july')->format('m'))
            ->whereYear('created_at', $request->selected_year)
            ->where('type', 'local')
            ->where('status', 'completed')
            ->count();

        $augustLocal = Transaction::whereMonth('created_at', Carbon::parse('august')->format('m'))
            ->whereYear('created_at', $request->selected_year)
            ->where('type', 'local')
            ->where('status', 'completed')
            ->count();

        $septemberLocal = Transaction::whereMonth('created_at', Carbon::parse('september')->format('m'))
            ->whereYear('created_at', $request->selected_year)
            ->where('type', 'local')
            ->where('status', 'completed')
            ->count();

        $octoberLocal = Transaction::whereMonth('created_at', Carbon::parse('october')->format('m'))
            ->whereYear('created_at', $request->selected_year)
            ->where('type', 'local')
            ->where('status', 'completed')
            ->count();

        $novemberLocal = Transaction::whereMonth('created_at', Carbon::parse('november')->format('m'))
            ->whereYear('created_at', $request->selected_year)
            ->where('type', 'local')
            ->where('status', 'completed')
            ->count();

        $decemberLocal = Transaction::whereMonth('created_at', Carbon::parse('december')->format('m'))
            ->whereYear('created_at', $request->selected_year)
            ->where('type', 'local')
            ->where('status', 'completed')
            ->count();

        // $january = Transaction::whereMonth('created_at', Carbon::parse('january')->format('m'))->whereYear('created_at', Carbon::parse('january')->format('Y'))->where('type', 'moncash')->where('status', 'completed')->count();
        // $february = Transaction::whereMonth('created_at', Carbon::parse('february')->format('m'))->whereYear('created_at', Carbon::parse('february')->format('Y'))->where('type', 'moncash')->where('status', 'completed')->count();
        // $march = Transaction::whereMonth('created_at', Carbon::parse('march')->format('m'))->whereYear('created_at', Carbon::parse('march')->format('Y'))->where('type', 'moncash')->where('status', 'completed')->count();
        // $april = Transaction::whereMonth('created_at', Carbon::parse('april')->format('m'))->whereYear('created_at', Carbon::parse('april')->format('Y'))->where('type', 'moncash')->where('status', 'completed')->count();
        // $may = Transaction::whereMonth('created_at', Carbon::parse('may')->format('m'))->whereYear('created_at', Carbon::parse('may')->format('Y'))->where('type', 'moncash')->where('status', 'completed')->count();
        // $june = Transaction::whereMonth('created_at', Carbon::parse('june')->format('m'))->whereYear('created_at', Carbon::parse('june')->format('Y'))->where('type', 'moncash')->where('status', 'completed')->count();
        // $july = Transaction::whereMonth('created_at', Carbon::parse('july')->format('m'))->whereYear('created_at', Carbon::parse('july')->format('Y'))->where('type', 'moncash')->where('status', 'completed')->count();
        // $august = Transaction::whereMonth('created_at', Carbon::parse('august')->format('m'))->whereYear('created_at', Carbon::parse('august')->format('Y'))->where('type', 'moncash')->where('status', 'completed')->count();
        // $september = Transaction::whereMonth('created_at', Carbon::parse('september')->format('m'))->whereYear('created_at', Carbon::parse('september')->format('Y'))->where('type', 'moncash')->where('status', 'completed')->count();
        // $october = Transaction::whereMonth('created_at', Carbon::parse('october')->format('m'))->whereYear('created_at', Carbon::parse('october')->format('Y'))->where('type', 'moncash')->where('status', 'completed')->count();
        // $november = Transaction::whereMonth('created_at', Carbon::parse('november')->format('m'))->whereYear('created_at', Carbon::parse('november')->format('Y'))->where('type', 'moncash')->where('status', 'completed')->count();
        // $december = Transaction::whereMonth('created_at', Carbon::parse('december')->format('m'))->whereYear('created_at', Carbon::parse('december')->format('Y'))->where('type', 'moncash')->where('status', 'completed')->c
        // $januaryNatcash = Transaction::whereMonth('created_at', Carbon::parse('january')->format('m'))->whereYear('created_at', Carbon::parse('january')->format('Y'))->where('type', 'natcash')->where('status', 'completed')->count();
        // $februaryNatcash = Transaction::whereMonth('created_at', Carbon::parse('february')->format('m'))->whereYear('created_at', Carbon::parse('february')->format('Y'))->where('type', 'natcash')->where('status', 'completed')->count();
        // $marchNatcash = Transaction::whereMonth('created_at', Carbon::parse('march')->format('m'))->whereYear('created_at', Carbon::parse('march')->format('Y'))->where('type', 'natcash')->where('status', 'completed')->count();
        // $aprilNatcash = Transaction::whereMonth('created_at', Carbon::parse('april')->format('m'))->whereYear('created_at', Carbon::parse('april')->format('Y'))->where('type', 'natcash')->where('status', 'completed')->count();
        // $mayNatcash = Transaction::whereMonth('created_at', Carbon::parse('may')->format('m'))->whereYear('created_at', Carbon::parse('may')->format('Y'))->where('type', 'natcash')->where('status', 'completed')->count();
        // $juneNatcash = Transaction::whereMonth('created_at', Carbon::parse('june')->format('m'))->whereYear('created_at', Carbon::parse('june')->format('Y'))->where('type', 'natcash')->where('status', 'completed')->count();
        // $julyNatcash = Transaction::whereMonth('created_at', Carbon::parse('july')->format('m'))->whereYear('created_at', Carbon::parse('july')->format('Y'))->where('type', 'natcash')->where('status', 'completed')->count();
        // $augustNatcash = Transaction::whereMonth('created_at', Carbon::parse('august')->format('m'))->whereYear('created_at', Carbon::parse('august')->format('Y'))->where('type', 'natcash')->where('status', 'completed')->count();
        // $septemberNatcash = Transaction::whereMonth('created_at', Carbon::parse('september')->format('m'))->whereYear('created_at', Carbon::parse('september')->format('Y'))->where('type', 'natcash')->where('status', 'completed')->count();
        // $octoberNatcash = Transaction::whereMonth('created_at', Carbon::parse('october')->format('m'))->whereYear('created_at', Carbon::parse('october')->format('Y'))->where('type', 'natcash')->where('status', 'completed')->count();
        // $novemberNatcash = Transaction::whereMonth('created_at', Carbon::parse('november')->format('m'))->whereYear('created_at', Carbon::parse('november')->format('Y'))->where('type', 'natcash')->where('status', 'completed')->count();
        // $decemberNatcash = Transaction::whereMonth('created_at', Carbon::parse('december')->format('m'))->whereYear('created_at', Carbon::parse('december')->format('Y'))->where('type', 'natcash')->where('status', 'completed')->count();

        // $januaryLocal = Transaction::whereMonth('created_at', Carbon::parse('january')->format('m'))->whereYear('created_at', Carbon::parse('january')->format('Y'))->where('type', 'local')->where('status', 'completed')->count();
        // $februaryLocal = Transaction::whereMonth('created_at', Carbon::parse('february')->format('m'))->whereYear('created_at', Carbon::parse('february')->format('Y'))->where('type', 'local')->where('status', 'completed')->count();
        // $marchLocal = Transaction::whereMonth('created_at', Carbon::parse('march')->format('m'))->whereYear('created_at', Carbon::parse('march')->format('Y'))->where('type', 'local')->where('status', 'completed')->count();
        // $aprilLocal = Transaction::whereMonth('created_at', Carbon::parse('april')->format('m'))->whereYear('created_at', Carbon::parse('april')->format('Y'))->where('type', 'local')->where('status', 'completed')->count();
        // $mayLocal = Transaction::whereMonth('created_at', Carbon::parse('may')->format('m'))->whereYear('created_at', Carbon::parse('may')->format('Y'))->where('type', 'local')->where('status', 'completed')->count();
        // $juneLocal = Transaction::whereMonth('created_at', Carbon::parse('june')->format('m'))->whereYear('created_at', Carbon::parse('june')->format('Y'))->where('type', 'local')->where('status', 'completed')->count();
        // $julyLocal = Transaction::whereMonth('created_at', Carbon::parse('july')->format('m'))->whereYear('created_at', Carbon::parse('july')->format('Y'))->where('type', 'local')->where('status', 'completed')->count();
        // $augustLocal = Transaction::whereMonth('created_at', Carbon::parse('august')->format('m'))->whereYear('created_at', Carbon::parse('august')->format('Y'))->where('type', 'local')->where('status', 'completed')->count();
        // $septemberLocal = Transaction::whereMonth('created_at', Carbon::parse('september')->format('m'))->whereYear('created_at', Carbon::parse('september')->format('Y'))->where('type', 'local')->where('status', 'completed')->count();
        // $octoberLocal = Transaction::whereMonth('created_at', Carbon::parse('october')->format('m'))->whereYear('created_at', Carbon::parse('october')->format('Y'))->where('type', 'local')->where('status', 'completed')->count();
        // $novemberLocal = Transaction::whereMonth('created_at', Carbon::parse('november')->format('m'))->whereYear('created_at', Carbon::parse('november')->format('Y'))->where('type', 'local')->where('status', 'completed')->count();
        // $decemberLocal = Transaction::whereMonth('created_at', Carbon::parse('december')->format('m'))->whereYear('created_at', Carbon::parse('december')->format('Y'))->where('type', 'local')->where('status', 'completed')->count();

        return [
            "allMoncashTransaction" => [$january, $february, $march, $april, $may, $june, $july, $august, $september, $october, $november, $december],
            "allNatcashTransaction" => [$januaryNatcash, $februaryNatcash, $marchNatcash, $aprilNatcash, $mayNatcash, $juneNatcash, $julyNatcash, $augustNatcash, $septemberNatcash, $octoberNatcash, $novemberNatcash, $decemberNatcash],
            "allLocalTransaction" => [$januaryLocal, $februaryLocal, $marchLocal, $aprilLocal, $mayLocal, $juneLocal, $julyLocal, $augustLocal, $septemberLocal, $octoberLocal, $novemberLocal, $decemberLocal],
        ];
    }

    public function getMonthlyUserTransactionChart(Request $request)
    {
        if ((Auth::user()->hasRole('agent'))) {
            $january = Transaction::where('user_id', Auth::id())
                ->whereMonth('created_at', Carbon::parse('january')->format('m'))
                ->whereYear('created_at', $request->selected_year)
                ->where('type', 'moncash')
                ->where('status', 'completed')
                ->count();

            $february = Transaction::where('user_id', Auth::id())
                ->whereMonth('created_at', Carbon::parse('february')->format('m'))
                ->whereYear('created_at', $request->selected_year)
                ->where('type', 'moncash')
                ->where('status', 'completed')
                ->count();

            $march = Transaction::where('user_id', Auth::id())
                ->whereMonth('created_at', Carbon::parse('march')->format('m'))
                ->whereYear('created_at', $request->selected_year)
                ->where('type', 'moncash')
                ->where('status', 'completed')
                ->count();

            $april = Transaction::where('user_id', Auth::id())
                ->whereMonth('created_at', Carbon::parse('april')->format('m'))
                ->whereYear('created_at', $request->selected_year)
                ->where('type', 'moncash')
                ->where('status', 'completed')
                ->count();

            $may = Transaction::where('user_id', Auth::id())
                ->whereMonth('created_at', Carbon::parse('may')->format('m'))
                ->whereYear('created_at', $request->selected_year)
                ->where('type', 'moncash')
                ->where('status', 'completed')
                ->count();

            $june = Transaction::where('user_id', Auth::id())
                ->whereMonth('created_at', Carbon::parse('june')->format('m'))
                ->whereYear('created_at', $request->selected_year)
                ->where('type', 'moncash')
                ->where('status', 'completed')
                ->count();

            $july = Transaction::where('user_id', Auth::id())
                ->whereMonth('created_at', Carbon::parse('july')->format('m'))
                ->whereYear('created_at', $request->selected_year)
                ->where('type', 'moncash')
                ->where('status', 'completed')
                ->count();

            $august = Transaction::where('user_id', Auth::id())
                ->whereMonth('created_at', Carbon::parse('august')->format('m'))
                ->whereYear('created_at', $request->selected_year)
                ->where('type', 'moncash')
                ->where('status', 'completed')
                ->count();

            $september = Transaction::where('user_id', Auth::id())
                ->whereMonth('created_at', Carbon::parse('september')->format('m'))
                ->whereYear('created_at', $request->selected_year)
                ->where('type', 'moncash')
                ->where('status', 'completed')
                ->count();

            $october = Transaction::where('user_id', Auth::id())
                ->whereMonth('created_at', Carbon::parse('october')->format('m'))
                ->whereYear('created_at', $request->selected_year)
                ->where('type', 'moncash')
                ->where('status', 'completed')
                ->count();

            $november = Transaction::where('user_id', Auth::id())
                ->whereMonth('created_at', Carbon::parse('november')->format('m'))
                ->whereYear('created_at', $request->selected_year)
                ->where('type', 'moncash')
                ->where('status', 'completed')
                ->count();

            $december = Transaction::where('user_id', Auth::id())
                ->whereMonth('created_at', Carbon::parse('december')->format('m'))
                ->whereYear('created_at', $request->selected_year)
                ->where('type', 'moncash')
                ->where('status', 'completed')
                ->count();

            $januaryNatcash = Transaction::where('user_id', Auth::id())
                ->whereMonth('created_at', Carbon::parse('january')->format('m'))
                ->whereYear('created_at', $request->selected_year)
                ->where('type', 'natcash')
                ->where('status', 'completed')
                ->count();

            $februaryNatcash = Transaction::where('user_id', Auth::id())
                ->whereMonth('created_at', Carbon::parse('february')->format('m'))
                ->whereYear('created_at', $request->selected_year)
                ->where('type', 'natcash')
                ->where('status', 'completed')
                ->count();

            $marchNatcash = Transaction::where('user_id', Auth::id())
                ->whereMonth('created_at', Carbon::parse('march')->format('m'))
                ->whereYear('created_at', $request->selected_year)
                ->where('type', 'natcash')
                ->where('status', 'completed')
                ->count();

            $aprilNatcash = Transaction::where('user_id', Auth::id())
                ->whereMonth('created_at', Carbon::parse('april')->format('m'))
                ->whereYear('created_at', $request->selected_year)
                ->where('type', 'natcash')
                ->where('status', 'completed')
                ->count();

            $mayNatcash = Transaction::where('user_id', Auth::id())
                ->whereMonth('created_at', Carbon::parse('may')->format('m'))
                ->whereYear('created_at', $request->selected_year)
                ->where('type', 'natcash')
                ->where('status', 'completed')
                ->count();

            $juneNatcash = Transaction::where('user_id', Auth::id())
                ->whereMonth('created_at', Carbon::parse('june')->format('m'))
                ->whereYear('created_at', $request->selected_year)
                ->where('type', 'natcash')
                ->where('status', 'completed')
                ->count();

            $julyNatcash = Transaction::where('user_id', Auth::id())
                ->whereMonth('created_at', Carbon::parse('july')->format('m'))
                ->whereYear('created_at', $request->selected_year)
                ->where('type', 'natcash')
                ->where('status', 'completed')
                ->count();

            $augustNatcash = Transaction::where('user_id', Auth::id())
                ->whereMonth('created_at', Carbon::parse('august')->format('m'))
                ->whereYear('created_at', $request->selected_year)
                ->where('type', 'natcash')
                ->where('status', 'completed')
                ->count();

            $septemberNatcash = Transaction::where('user_id', Auth::id())
                ->whereMonth('created_at', Carbon::parse('september')->format('m'))
                ->whereYear('created_at', $request->selected_year)
                ->where('type', 'natcash')
                ->where('status', 'completed')
                ->count();

            $octoberNatcash = Transaction::where('user_id', Auth::id())
                ->whereMonth('created_at', Carbon::parse('october')->format('m'))
                ->whereYear('created_at', $request->selected_year)
                ->where('type', 'natcash')
                ->where('status', 'completed')
                ->count();

            $novemberNatcash = Transaction::where('user_id', Auth::id())
                ->whereMonth('created_at', Carbon::parse('november')->format('m'))
                ->whereYear('created_at', $request->selected_year)
                ->where('type', 'natcash')
                ->where('status', 'completed')
                ->count();

            $decemberNatcash = Transaction::where('user_id', Auth::id())
                ->whereMonth('created_at', Carbon::parse('december')->format('m'))
                ->whereYear('created_at', $request->selected_year)
                ->where('type', 'natcash')
                ->where('status', 'completed')
                ->count();

            $januaryLocal = Transaction::where('user_id', Auth::id())
                ->whereMonth('created_at', Carbon::parse('january')->format('m'))
                ->whereYear('created_at', $request->selected_year)
                ->where('type', 'local')
                ->where('status', 'completed')
                ->count();

            $februaryLocal = Transaction::where('user_id', Auth::id())
                ->whereMonth('created_at', Carbon::parse('february')->format('m'))
                ->whereYear('created_at', $request->selected_year)
                ->where('type', 'local')
                ->where('status', 'completed')
                ->count();

            $marchLocal = Transaction::where('user_id', Auth::id())
                ->whereMonth('created_at', Carbon::parse('march')->format('m'))
                ->whereYear('created_at', $request->selected_year)
                ->where('type', 'local')
                ->where('status', 'completed')
                ->count();

            $aprilLocal = Transaction::where('user_id', Auth::id())
                ->whereMonth('created_at', Carbon::parse('april')->format('m'))
                ->whereYear('created_at', $request->selected_year)
                ->where('type', 'local')
                ->where('status', 'completed')
                ->count();

            $mayLocal = Transaction::where('user_id', Auth::id())
                ->whereMonth('created_at', Carbon::parse('may')->format('m'))
                ->whereYear('created_at', $request->selected_year)
                ->where('type', 'local')
                ->where('status', 'completed')
                ->count();

            $juneLocal = Transaction::where('user_id', Auth::id())
                ->whereMonth('created_at', Carbon::parse('june')->format('m'))
                ->whereYear('created_at', $request->selected_year)
                ->where('type', 'local')
                ->where('status', 'completed')
                ->count();

            $julyLocal = Transaction::where('user_id', Auth::id())
                ->whereMonth('created_at', Carbon::parse('july')->format('m'))
                ->whereYear('created_at', $request->selected_year)
                ->where('type', 'local')
                ->where('status', 'completed')
                ->count();

            $augustLocal = Transaction::where('user_id', Auth::id())
                ->whereMonth('created_at', Carbon::parse('august')->format('m'))
                ->whereYear('created_at', $request->selected_year)
                ->where('type', 'local')
                ->where('status', 'completed')
                ->count();

            $septemberLocal = Transaction::where('user_id', Auth::id())
                ->whereMonth('created_at', Carbon::parse('september')->format('m'))
                ->whereYear('created_at', $request->selected_year)
                ->where('type', 'local')
                ->where('status', 'completed')
                ->count();

            $octoberLocal = Transaction::where('user_id', Auth::id())
                ->whereMonth('created_at', Carbon::parse('october')->format('m'))
                ->whereYear('created_at', $request->selected_year)
                ->where('type', 'local')
                ->where('status', 'completed')
                ->count();

            $novemberLocal = Transaction::where('user_id', Auth::id())
                ->whereMonth('created_at', Carbon::parse('november')->format('m'))
                ->whereYear('created_at', $request->selected_year)
                ->where('type', 'local')
                ->where('status', 'completed')
                ->count();

            $decemberLocal = Transaction::where('user_id', Auth::id())
                ->whereMonth('created_at', Carbon::parse('december')->format('m'))
                ->whereYear('created_at', $request->selected_year)
                ->where('type', 'local')
                ->where('status', 'completed')
                ->count();
            // $january = Transaction::where('user_id', Auth::id())->whereMonth('created_at', Carbon::parse('january')->format('m'))->whereYear('created_at', Carbon::parse('january')->format('Y'))->where('type', 'moncash')->where('status', 'completed')->count();
            // $february = Transaction::where('user_id', Auth::id())->whereMonth('created_at', Carbon::parse('february')->format('m'))->whereYear('created_at', Carbon::parse('february')->format('Y'))->where('type', 'moncash')->where('status', 'completed')->count();
            // $march = Transaction::where('user_id', Auth::id())->whereMonth('created_at', Carbon::parse('march')->format('m'))->whereYear('created_at', Carbon::parse('march')->format('Y'))->where('type', 'moncash')->where('status', 'completed')->count();
            // $april = Transaction::where('user_id', Auth::id())->whereMonth('created_at', Carbon::parse('april')->format('m'))->whereYear('created_at', Carbon::parse('april')->format('Y'))->where('type', 'moncash')->where('status', 'completed')->count();
            // $may = Transaction::where('user_id', Auth::id())->whereMonth('created_at', Carbon::parse('may')->format('m'))->whereYear('created_at', Carbon::parse('may')->format('Y'))->where('type', 'moncash')->where('status', 'completed')->count();
            // $june = Transaction::where('user_id', Auth::id())->whereMonth('created_at', Carbon::parse('june')->format('m'))->whereYear('created_at', Carbon::parse('june')->format('Y'))->where('type', 'moncash')->where('status', 'completed')->count();
            // $july = Transaction::where('user_id', Auth::id())->whereMonth('created_at', Carbon::parse('july')->format('m'))->whereYear('created_at', Carbon::parse('july')->format('Y'))->where('type', 'moncash')->where('status', 'completed')->count();
            // $august = Transaction::where('user_id', Auth::id())->whereMonth('created_at', Carbon::parse('august')->format('m'))->whereYear('created_at', Carbon::parse('august')->format('Y'))->where('type', 'moncash')->where('status', 'completed')->count();
            // $september = Transaction::where('user_id', Auth::id())->whereMonth('created_at', Carbon::parse('september')->format('m'))->whereYear('created_at', Carbon::parse('september')->format('Y'))->where('type', 'moncash')->where('status', 'completed')->count();
            // $october = Transaction::where('user_id', Auth::id())->whereMonth('created_at', Carbon::parse('october')->format('m'))->whereYear('created_at', Carbon::parse('october')->format('Y'))->where('type', 'moncash')->where('status', 'completed')->count();
            // $november = Transaction::where('user_id', Auth::id())->whereMonth('created_at', Carbon::parse('november')->format('m'))->whereYear('created_at', Carbon::parse('november')->format('Y'))->where('type', 'moncash')->where('status', 'completed')->count();
            // $december = Transaction::where('user_id', Auth::id())->whereMonth('created_at', Carbon::parse('december')->format('m'))->whereYear('created_at', Carbon::parse('december')->format('Y'))->where('type', 'moncash')->where('status', 'completed')->count();

            // $januaryNatcash = Transaction::where('user_id', Auth::id())->whereMonth('created_at', Carbon::parse('january')->format('m'))->whereYear('created_at', Carbon::parse('january')->format('Y'))->where('type', 'natcash')->where('status', 'completed')->count();
            // $februaryNatcash = Transaction::where('user_id', Auth::id())->whereMonth('created_at', Carbon::parse('february')->format('m'))->whereYear('created_at', Carbon::parse('february')->format('Y'))->where('type', 'natcash')->where('status', 'completed')->count();
            // $marchNatcash = Transaction::where('user_id', Auth::id())->whereMonth('created_at', Carbon::parse('march')->format('m'))->whereYear('created_at', Carbon::parse('march')->format('Y'))->where('type', 'natcash')->where('status', 'completed')->count();
            // $aprilNatcash = Transaction::where('user_id', Auth::id())->whereMonth('created_at', Carbon::parse('april')->format('m'))->whereYear('created_at', Carbon::parse('april')->format('Y'))->where('type', 'natcash')->where('status', 'completed')->count();
            // $mayNatcash = Transaction::where('user_id', Auth::id())->whereMonth('created_at', Carbon::parse('may')->format('m'))->whereYear('created_at', Carbon::parse('may')->format('Y'))->where('type', 'natcash')->where('status', 'completed')->count();
            // $juneNatcash = Transaction::where('user_id', Auth::id())->whereMonth('created_at', Carbon::parse('june')->format('m'))->whereYear('created_at', Carbon::parse('june')->format('Y'))->where('type', 'natcash')->where('status', 'completed')->count();
            // $julyNatcash = Transaction::where('user_id', Auth::id())->whereMonth('created_at', Carbon::parse('july')->format('m'))->whereYear('created_at', Carbon::parse('july')->format('Y'))->where('type', 'natcash')->where('status', 'completed')->count();
            // $augustNatcash = Transaction::where('user_id', Auth::id())->whereMonth('created_at', Carbon::parse('august')->format('m'))->whereYear('created_at', Carbon::parse('august')->format('Y'))->where('type', 'natcash')->where('status', 'completed')->count();
            // $septemberNatcash = Transaction::where('user_id', Auth::id())->whereMonth('created_at', Carbon::parse('september')->format('m'))->whereYear('created_at', Carbon::parse('september')->format('Y'))->where('type', 'natcash')->where('status', 'completed')->count();
            // $octoberNatcash = Transaction::where('user_id', Auth::id())->whereMonth('created_at', Carbon::parse('october')->format('m'))->whereYear('created_at', Carbon::parse('october')->format('Y'))->where('type', 'natcash')->where('status', 'completed')->count();
            // $novemberNatcash = Transaction::where('user_id', Auth::id())->whereMonth('created_at', Carbon::parse('november')->format('m'))->whereYear('created_at', Carbon::parse('november')->format('Y'))->where('type', 'natcash')->where('status', 'completed')->count();
            // $decemberNatcash = Transaction::where('user_id', Auth::id())->whereMonth('created_at', Carbon::parse('december')->format('m'))->whereYear('created_at', Carbon::parse('december')->format('Y'))->where('type', 'natcash')->where('status', 'completed')->count();

            // $januaryLocal = Transaction::where('user_id', Auth::id())->whereMonth('created_at', Carbon::parse('january')->format('m'))->whereYear('created_at', Carbon::parse('january')->format('Y'))->where('type', 'local')->where('status', 'completed')->count();
            // $februaryLocal = Transaction::where('user_id', Auth::id())->whereMonth('created_at', Carbon::parse('february')->format('m'))->whereYear('created_at', Carbon::parse('february')->format('Y'))->where('type', 'local')->where('status', 'completed')->count();
            // $marchLocal = Transaction::where('user_id', Auth::id())->whereMonth('created_at', Carbon::parse('march')->format('m'))->whereYear('created_at', Carbon::parse('march')->format('Y'))->where('type', 'local')->where('status', 'completed')->count();
            // $aprilLocal = Transaction::where('user_id', Auth::id())->whereMonth('created_at', Carbon::parse('april')->format('m'))->whereYear('created_at', Carbon::parse('april')->format('Y'))->where('type', 'local')->where('status', 'completed')->count();
            // $mayLocal = Transaction::where('user_id', Auth::id())->whereMonth('created_at', Carbon::parse('may')->format('m'))->whereYear('created_at', Carbon::parse('may')->format('Y'))->where('type', 'local')->where('status', 'completed')->count();
            // $juneLocal = Transaction::where('user_id', Auth::id())->whereMonth('created_at', Carbon::parse('june')->format('m'))->whereYear('created_at', Carbon::parse('june')->format('Y'))->where('type', 'local')->where('status', 'completed')->count();
            // $julyLocal = Transaction::where('user_id', Auth::id())->whereMonth('created_at', Carbon::parse('july')->format('m'))->whereYear('created_at', Carbon::parse('july')->format('Y'))->where('type', 'local')->where('status', 'completed')->count();
            // $augustLocal = Transaction::where('user_id', Auth::id())->whereMonth('created_at', Carbon::parse('august')->format('m'))->whereYear('created_at', Carbon::parse('august')->format('Y'))->where('type', 'local')->where('status', 'completed')->count();
            // $septemberLocal = Transaction::where('user_id', Auth::id())->whereMonth('created_at', Carbon::parse('september')->format('m'))->whereYear('created_at', Carbon::parse('september')->format('Y'))->where('type', 'local')->where('status', 'completed')->count();
            // $octoberLocal = Transaction::where('user_id', Auth::id())->whereMonth('created_at', Carbon::parse('october')->format('m'))->whereYear('created_at', Carbon::parse('october')->format('Y'))->where('type', 'local')->where('status', 'completed')->count();
            // $novemberLocal = Transaction::where('user_id', Auth::id())->whereMonth('created_at', Carbon::parse('november')->format('m'))->whereYear('created_at', Carbon::parse('november')->format('Y'))->where('type', 'local')->where('status', 'completed')->count();
            // $decemberLocal = Transaction::where('user_id', Auth::id())->whereMonth('created_at', Carbon::parse('december')->format('m'))->whereYear('created_at', Carbon::parse('december')->format('Y'))->where('type', 'local')->where('status', 'completed')->count();

            return [
                "allMoncashTransaction" => [$january, $february, $march, $april, $may, $june, $july, $august, $september, $october, $november, $december],
                "allNatcashTransaction" => [$januaryNatcash, $februaryNatcash, $marchNatcash, $aprilNatcash, $mayNatcash, $juneNatcash, $julyNatcash, $augustNatcash, $septemberNatcash, $octoberNatcash, $novemberNatcash, $decemberNatcash],
                "allLocalTransaction" => [$januaryLocal, $februaryLocal, $marchLocal, $aprilLocal, $mayLocal, $juneLocal, $julyLocal, $augustLocal, $septemberLocal, $octoberLocal, $novemberLocal, $decemberLocal],
            ];
        }
        if ((Auth::user()->hasRole('operator'))) {
            // Moncash transactions
            $january = Transaction::where('operator_id', Auth::id())
                ->whereMonth('completed_date', Carbon::parse('january')->format('m'))
                ->whereYear('completed_date', $request->selected_year)
                ->where('type', 'moncash')
                ->where('status', 'completed')
                ->count();

            $february = Transaction::where('operator_id', Auth::id())
                ->whereMonth('completed_date', Carbon::parse('february')->format('m'))
                ->whereYear('completed_date', $request->selected_year)
                ->where('type', 'moncash')
                ->where('status', 'completed')
                ->count();

            $march = Transaction::where('operator_id', Auth::id())
                ->whereMonth('completed_date', Carbon::parse('march')->format('m'))
                ->whereYear('completed_date', $request->selected_year)
                ->where('type', 'moncash')
                ->where('status', 'completed')
                ->count();

            $april = Transaction::where('operator_id', Auth::id())
                ->whereMonth('completed_date', Carbon::parse('april')->format('m'))
                ->whereYear('completed_date', $request->selected_year)
                ->where('type', 'moncash')
                ->where('status', 'completed')
                ->count();

            $may = Transaction::where('operator_id', Auth::id())
                ->whereMonth('completed_date', Carbon::parse('may')->format('m'))
                ->whereYear('completed_date', $request->selected_year)
                ->where('type', 'moncash')
                ->where('status', 'completed')
                ->count();

            $june = Transaction::where('operator_id', Auth::id())
                ->whereMonth('completed_date', Carbon::parse('june')->format('m'))
                ->whereYear('completed_date', $request->selected_year)
                ->where('type', 'moncash')
                ->where('status', 'completed')
                ->count();

            $july = Transaction::where('operator_id', Auth::id())
                ->whereMonth('completed_date', Carbon::parse('july')->format('m'))
                ->whereYear('completed_date', $request->selected_year)
                ->where('type', 'moncash')
                ->where('status', 'completed')
                ->count();

            $august = Transaction::where('operator_id', Auth::id())
                ->whereMonth('completed_date', Carbon::parse('august')->format('m'))
                ->whereYear('completed_date', $request->selected_year)
                ->where('type', 'moncash')
                ->where('status', 'completed')
                ->count();

            $september = Transaction::where('operator_id', Auth::id())
                ->whereMonth('completed_date', Carbon::parse('september')->format('m'))
                ->whereYear('completed_date', $request->selected_year)
                ->where('type', 'moncash')
                ->where('status', 'completed')
                ->count();

            $october = Transaction::where('operator_id', Auth::id())
                ->whereMonth('completed_date', Carbon::parse('october')->format('m'))
                ->whereYear('completed_date', $request->selected_year)
                ->where('type', 'moncash')
                ->where('status', 'completed')
                ->count();

            $november = Transaction::where('operator_id', Auth::id())
                ->whereMonth('completed_date', Carbon::parse('november')->format('m'))
                ->whereYear('completed_date', $request->selected_year)
                ->where('type', 'moncash')
                ->where('status', 'completed')
                ->count();

            $december = Transaction::where('operator_id', Auth::id())
                ->whereMonth('completed_date', Carbon::parse('december')->format('m'))
                ->whereYear('completed_date', $request->selected_year)
                ->where('type', 'moncash')
                ->where('status', 'completed')
                ->count();

            // Natcash transactions
            $januaryNatcash = Transaction::where('operator_id', Auth::id())
                ->whereMonth('completed_date', Carbon::parse('january')->format('m'))
                ->whereYear('completed_date', $request->selected_year)
                ->where('type', 'natcash')
                ->where('status', 'completed')
                ->count();

            $februaryNatcash = Transaction::where('operator_id', Auth::id())
                ->whereMonth('completed_date', Carbon::parse('february')->format('m'))
                ->whereYear('completed_date', $request->selected_year)
                ->where('type', 'natcash')
                ->where('status', 'completed')
                ->count();

            $marchNatcash = Transaction::where('operator_id', Auth::id())
                ->whereMonth('completed_date', Carbon::parse('march')->format('m'))
                ->whereYear('completed_date', $request->selected_year)
                ->where('type', 'natcash')
                ->where('status', 'completed')
                ->count();

            $aprilNatcash = Transaction::where('operator_id', Auth::id())
                ->whereMonth('completed_date', Carbon::parse('april')->format('m'))
                ->whereYear('completed_date', $request->selected_year)
                ->where('type', 'natcash')
                ->where('status', 'completed')
                ->count();

            $mayNatcash = Transaction::where('operator_id', Auth::id())
                ->whereMonth('completed_date', Carbon::parse('may')->format('m'))
                ->whereYear('completed_date', $request->selected_year)
                ->where('type', 'natcash')
                ->where('status', 'completed')
                ->count();

            $juneNatcash = Transaction::where('operator_id', Auth::id())
                ->whereMonth('completed_date', Carbon::parse('june')->format('m'))
                ->whereYear('completed_date', $request->selected_year)
                ->where('type', 'natcash')
                ->where('status', 'completed')
                ->count();

            $julyNatcash = Transaction::where('operator_id', Auth::id())
                ->whereMonth('completed_date', Carbon::parse('july')->format('m'))
                ->whereYear('completed_date', $request->selected_year)
                ->where('type', 'natcash')
                ->where('status', 'completed')
                ->count();

            $augustNatcash = Transaction::where('operator_id', Auth::id())
                ->whereMonth('completed_date', Carbon::parse('august')->format('m'))
                ->whereYear('completed_date', $request->selected_year)
                ->where('type', 'natcash')
                ->where('status', 'completed')
                ->count();

            $septemberNatcash = Transaction::where('operator_id', Auth::id())
                ->whereMonth('completed_date', Carbon::parse('september')->format('m'))
                ->whereYear('completed_date', $request->selected_year)
                ->where('type', 'natcash')
                ->where('status', 'completed')
                ->count();

            $octoberNatcash = Transaction::where('operator_id', Auth::id())
                ->whereMonth('completed_date', Carbon::parse('october')->format('m'))
                ->whereYear('completed_date', $request->selected_year)
                ->where('type', 'natcash')
                ->where('status', 'completed')
                ->count();

            $novemberNatcash = Transaction::where('operator_id', Auth::id())
                ->whereMonth('completed_date', Carbon::parse('november')->format('m'))
                ->whereYear('completed_date', $request->selected_year)
                ->where('type', 'natcash')
                ->where('status', 'completed')
                ->count();

            $decemberNatcash = Transaction::where('operator_id', Auth::id())
                ->whereMonth('completed_date', Carbon::parse('december')->format('m'))
                ->whereYear('completed_date', $request->selected_year)
                ->where('type', 'natcash')
                ->where('status', 'completed')
                ->count();

            // Local transactions
            $januaryLocal = Transaction::where('operator_id', Auth::id())
                ->whereMonth('completed_date', Carbon::parse('january')->format('m'))
                ->whereYear('completed_date', $request->selected_year)
                ->where('type', 'local')
                ->where('status', 'completed')
                ->count();

            $februaryLocal = Transaction::where('operator_id', Auth::id())
                ->whereMonth('completed_date', Carbon::parse('february')->format('m'))
                ->whereYear('completed_date', $request->selected_year)
                ->where('type', 'local')
                ->where('status', 'completed')
                ->count();

            $marchLocal = Transaction::where('operator_id', Auth::id())
                ->whereMonth('completed_date', Carbon::parse('march')->format('m'))
                ->whereYear('completed_date', $request->selected_year)
                ->where('type', 'local')
                ->where('status', 'completed')
                ->count();

            $aprilLocal = Transaction::where('operator_id', Auth::id())
                ->whereMonth('completed_date', Carbon::parse('april')->format('m'))
                ->whereYear('completed_date', $request->selected_year)
                ->where('type', 'local')
                ->where('status', 'completed')
                ->count();

            $mayLocal = Transaction::where('operator_id', Auth::id())
                ->whereMonth('completed_date', Carbon::parse('may')->format('m'))
                ->whereYear('completed_date', $request->selected_year)
                ->where('type', 'local')
                ->where('status', 'completed')
                ->count();

            $juneLocal = Transaction::where('operator_id', Auth::id())
                ->whereMonth('completed_date', Carbon::parse('june')->format('m'))
                ->whereYear('completed_date', $request->selected_year)
                ->where('type', 'local')
                ->where('status', 'completed')
                ->count();

            $julyLocal = Transaction::where('operator_id', Auth::id())
                ->whereMonth('completed_date', Carbon::parse('july')->format('m'))
                ->whereYear('completed_date', $request->selected_year)
                ->where('type', 'local')
                ->where('status', 'completed')
                ->count();

            $augustLocal = Transaction::where('operator_id', Auth::id())
                ->whereMonth('completed_date', Carbon::parse('august')->format('m'))
                ->whereYear('completed_date', $request->selected_year)
                ->where('type', 'local')
                ->where('status', 'completed')
                ->count();

            $septemberLocal = Transaction::where('operator_id', Auth::id())
                ->whereMonth('completed_date', Carbon::parse('september')->format('m'))
                ->whereYear('completed_date', $request->selected_year)
                ->where('type', 'local')
                ->where('status', 'completed')
                ->count();

            $octoberLocal = Transaction::where('operator_id', Auth::id())
                ->whereMonth('completed_date', Carbon::parse('october')->format('m'))
                ->whereYear('completed_date', $request->selected_year)
                ->where('type', 'local')
                ->where('status', 'completed')
                ->count();

            $novemberLocal = Transaction::where('operator_id', Auth::id())
                ->whereMonth('completed_date', Carbon::parse('november')->format('m'))
                ->whereYear('completed_date', $request->selected_year)
                ->where('type', 'local')
                ->where('status', 'completed')
                ->count();

            $decemberLocal = Transaction::where('operator_id', Auth::id())
                ->whereMonth('completed_date', Carbon::parse('december')->format('m'))
                ->whereYear('completed_date', $request->selected_year)
                ->where('type', 'local')
                ->where('status', 'completed')
                ->count();

            // $january = Transaction::where('operator_id', Auth::id())->whereMonth('completed_date', Carbon::parse('january')->format('m'))->whereYear('completed_date', Carbon::parse('january')->format('Y'))->where('type', 'moncash')->where('status', 'completed')->count();
            // $february = Transaction::where('operator_id', Auth::id())->whereMonth('completed_date', Carbon::parse('february')->format('m'))->whereYear('completed_date', Carbon::parse('february')->format('Y'))->where('type', 'moncash')->where('status', 'completed')->count();
            // $march = Transaction::where('operator_id', Auth::id())->whereMonth('completed_date', Carbon::parse('march')->format('m'))->whereYear('completed_date', Carbon::parse('march')->format('Y'))->where('type', 'moncash')->where('status', 'completed')->count();
            // $april = Transaction::where('operator_id', Auth::id())->whereMonth('completed_date', Carbon::parse('april')->format('m'))->whereYear('completed_date', Carbon::parse('april')->format('Y'))->where('type', 'moncash')->where('status', 'completed')->count();
            // $may = Transaction::where('operator_id', Auth::id())->whereMonth('completed_date', Carbon::parse('may')->format('m'))->whereYear('completed_date', Carbon::parse('may')->format('Y'))->where('type', 'moncash')->where('status', 'completed')->count();
            // $june = Transaction::where('operator_id', Auth::id())->whereMonth('completed_date', Carbon::parse('june')->format('m'))->whereYear('completed_date', Carbon::parse('june')->format('Y'))->where('type', 'moncash')->where('status', 'completed')->count();
            // $july = Transaction::where('operator_id', Auth::id())->whereMonth('completed_date', Carbon::parse('july')->format('m'))->whereYear('completed_date', Carbon::parse('july')->format('Y'))->where('type', 'moncash')->where('status', 'completed')->count();
            // $august = Transaction::where('operator_id', Auth::id())->whereMonth('completed_date', Carbon::parse('august')->format('m'))->whereYear('completed_date', Carbon::parse('august')->format('Y'))->where('type', 'moncash')->where('status', 'completed')->count();
            // $september = Transaction::where('operator_id', Auth::id())->whereMonth('completed_date', Carbon::parse('september')->format('m'))->whereYear('completed_date', Carbon::parse('september')->format('Y'))->where('type', 'moncash')->where('status', 'completed')->count();
            // $october = Transaction::where('operator_id', Auth::id())->whereMonth('completed_date', Carbon::parse('october')->format('m'))->whereYear('completed_date', Carbon::parse('october')->format('Y'))->where('type', 'moncash')->where('status', 'completed')->count();
            // $november = Transaction::where('operator_id', Auth::id())->whereMonth('completed_date', Carbon::parse('november')->format('m'))->whereYear('completed_date', Carbon::parse('november')->format('Y'))->where('type', 'moncash')->where('status', 'completed')->count();
            // $december = Transaction::where('operator_id', Auth::id())->whereMonth('completed_date', Carbon::parse('december')->format('m'))->whereYear('completed_date', Carbon::parse('december')->format('Y'))->where('type', 'moncash')->where('status', 'completed')->count();

            // $januaryNatcash = Transaction::where('operator_id', Auth::id())->whereMonth('completed_date', Carbon::parse('january')->format('m'))->whereYear('completed_date', Carbon::parse('january')->format('Y'))->where('type', 'natcash')->where('status', 'completed')->count();
            // $februaryNatcash = Transaction::where('operator_id', Auth::id())->whereMonth('completed_date', Carbon::parse('february')->format('m'))->whereYear('completed_date', Carbon::parse('february')->format('Y'))->where('type', 'natcash')->where('status', 'completed')->count();
            // $marchNatcash = Transaction::where('operator_id', Auth::id())->whereMonth('completed_date', Carbon::parse('march')->format('m'))->whereYear('completed_date', Carbon::parse('march')->format('Y'))->where('type', 'natcash')->where('status', 'completed')->count();
            // $aprilNatcash = Transaction::where('operator_id', Auth::id())->whereMonth('completed_date', Carbon::parse('april')->format('m'))->whereYear('completed_date', Carbon::parse('april')->format('Y'))->where('type', 'natcash')->where('status', 'completed')->count();
            // $mayNatcash = Transaction::where('operator_id', Auth::id())->whereMonth('completed_date', Carbon::parse('may')->format('m'))->whereYear('completed_date', Carbon::parse('may')->format('Y'))->where('type', 'natcash')->where('status', 'completed')->count();
            // $juneNatcash = Transaction::where('operator_id', Auth::id())->whereMonth('completed_date', Carbon::parse('june')->format('m'))->whereYear('completed_date', Carbon::parse('june')->format('Y'))->where('type', 'natcash')->where('status', 'completed')->count();
            // $julyNatcash = Transaction::where('operator_id', Auth::id())->whereMonth('completed_date', Carbon::parse('july')->format('m'))->whereYear('completed_date', Carbon::parse('july')->format('Y'))->where('type', 'natcash')->where('status', 'completed')->count();
            // $augustNatcash = Transaction::where('operator_id', Auth::id())->whereMonth('completed_date', Carbon::parse('august')->format('m'))->whereYear('completed_date', Carbon::parse('august')->format('Y'))->where('type', 'natcash')->where('status', 'completed')->count();
            // $septemberNatcash = Transaction::where('operator_id', Auth::id())->whereMonth('completed_date', Carbon::parse('september')->format('m'))->whereYear('completed_date', Carbon::parse('september')->format('Y'))->where('type', 'natcash')->where('status', 'completed')->count();
            // $octoberNatcash = Transaction::where('operator_id', Auth::id())->whereMonth('completed_date', Carbon::parse('october')->format('m'))->whereYear('completed_date', Carbon::parse('october')->format('Y'))->where('type', 'natcash')->where('status', 'completed')->count();
            // $novemberNatcash = Transaction::where('operator_id', Auth::id())->whereMonth('completed_date', Carbon::parse('november')->format('m'))->whereYear('completed_date', Carbon::parse('november')->format('Y'))->where('type', 'natcash')->where('status', 'completed')->count();
            // $decemberNatcash = Transaction::where('operator_id', Auth::id())->whereMonth('completed_date', Carbon::parse('december')->format('m'))->whereYear('completed_date', Carbon::parse('december')->format('Y'))->where('type', 'natcash')->where('status', 'completed')->count();

            // $januaryLocal = Transaction::where('operator_id', Auth::id())->whereMonth('completed_date', Carbon::parse('january')->format('m'))->whereYear('completed_date', Carbon::parse('january')->format('Y'))->where('type', 'local')->where('status', 'completed')->count();
            // $februaryLocal = Transaction::where('operator_id', Auth::id())->whereMonth('completed_date', Carbon::parse('february')->format('m'))->whereYear('completed_date', Carbon::parse('february')->format('Y'))->where('type', 'local')->where('status', 'completed')->count();
            // $marchLocal = Transaction::where('operator_id', Auth::id())->whereMonth('completed_date', Carbon::parse('march')->format('m'))->whereYear('completed_date', Carbon::parse('march')->format('Y'))->where('type', 'local')->where('status', 'completed')->count();
            // $aprilLocal = Transaction::where('operator_id', Auth::id())->whereMonth('completed_date', Carbon::parse('april')->format('m'))->whereYear('completed_date', Carbon::parse('april')->format('Y'))->where('type', 'local')->where('status', 'completed')->count();
            // $mayLocal = Transaction::where('operator_id', Auth::id())->whereMonth('completed_date', Carbon::parse('may')->format('m'))->whereYear('completed_date', Carbon::parse('may')->format('Y'))->where('type', 'local')->where('status', 'completed')->count();
            // $juneLocal = Transaction::where('operator_id', Auth::id())->whereMonth('completed_date', Carbon::parse('june')->format('m'))->whereYear('completed_date', Carbon::parse('june')->format('Y'))->where('type', 'local')->where('status', 'completed')->count();
            // $julyLocal = Transaction::where('operator_id', Auth::id())->whereMonth('completed_date', Carbon::parse('july')->format('m'))->whereYear('completed_date', Carbon::parse('july')->format('Y'))->where('type', 'local')->where('status', 'completed')->count();
            // $augustLocal = Transaction::where('operator_id', Auth::id())->whereMonth('completed_date', Carbon::parse('august')->format('m'))->whereYear('completed_date', Carbon::parse('august')->format('Y'))->where('type', 'local')->where('status', 'completed')->count();
            // $septemberLocal = Transaction::where('operator_id', Auth::id())->whereMonth('completed_date', Carbon::parse('september')->format('m'))->whereYear('completed_date', Carbon::parse('september')->format('Y'))->where('type', 'local')->where('status', 'completed')->count();
            // $octoberLocal = Transaction::where('operator_id', Auth::id())->whereMonth('completed_date', Carbon::parse('october')->format('m'))->whereYear('completed_date', Carbon::parse('october')->format('Y'))->where('type', 'local')->where('status', 'completed')->count();
            // $novemberLocal = Transaction::where('operator_id', Auth::id())->whereMonth('completed_date', Carbon::parse('november')->format('m'))->whereYear('completed_date', Carbon::parse('november')->format('Y'))->where('type', 'local')->where('status', 'completed')->count();
            // $decemberLocal = Transaction::where('operator_id', Auth::id())->whereMonth('completed_date', Carbon::parse('december')->format('m'))->whereYear('completed_date', Carbon::parse('december')->format('Y'))->where('type', 'local')->where('status', 'completed')->count();

            return [
                "allMoncashTransaction" => [$january, $february, $march, $april, $may, $june, $july, $august, $september, $october, $november, $december],
                "allNatcashTransaction" => [$januaryNatcash, $februaryNatcash, $marchNatcash, $aprilNatcash, $mayNatcash, $juneNatcash, $julyNatcash, $augustNatcash, $septemberNatcash, $octoberNatcash, $novemberNatcash, $decemberNatcash],
                "allLocalTransaction" => [$januaryLocal, $februaryLocal, $marchLocal, $aprilLocal, $mayLocal, $juneLocal, $julyLocal, $augustLocal, $septemberLocal, $octoberLocal, $novemberLocal, $decemberLocal],
            ];
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TransactionFormRequest $request)
    {
        set_time_limit(120);
        return $this->transactionStoreService->storeTransaction($request);
       // return $this->transactionCheckInvervalService->checkIntervalTransaction($request, $checkSender, $checkReceiver, $user, $withdrawal, $operator);
    }

    public function reviewDisapprovedTransaction(TransactionFormRequest $request)
    {
        set_time_limit(120);
        // Check sender and receiver
        $checkSender = Client::where('phone', $request->sender_phone)->first();
        $checkReceiver = Client::where('phone', $request->receiver_phone)->first();
        $user = User::with(['user_account'])->findOrFail(Auth::id());
        $withdrawal = SendMoney::where('receiver_id', Auth::id())->where('status', 'pending')->sum('amount');

        // get transaction
        $transaction = Transaction::findOrFail($request->transaction_id);

        return $this->transactionReviewService->reviewTransaction($request, $transaction, $user, $checkSender, $checkReceiver, $withdrawal);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        $transaction = Transaction::findOrFail($transaction->id);
        return Transaction::with(['client', 'user'])->orderBy('created_at', 'DESC')->findOrFail($transaction->id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */


    public function updateTransactionStatus(Request $request)
    {
        return $this->transactionApproveOrDisapproveService->approveOrDisapproveStatus($request);
    }

    public function updateTransactionStatusCompleted(ApprovalCompletedFormRequest $request)
    {
        return $this->transactionCompletionService->completeTransaction($request);
    }

    public function update(TransactionFormRequest $request, Transaction $transaction)
    {
        $transaction = User::findOrFail(1);
        $transaction->update(['status' => $request->status]);

        return Transaction::with(['client'])->orderBy('created_at', 'DESC')->paginate(20);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
