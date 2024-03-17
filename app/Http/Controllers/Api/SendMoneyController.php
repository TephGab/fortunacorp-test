<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use App\Models\SendMoney;
use Illuminate\Http\Request;
use App\Models\SystemAccount;
use App\Models\SystemBankAccount;
use App\Http\Controllers\Controller;
use App\Rules\ValidateReplenishment;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SendMoneyRequest;
use App\Rules\CheckIfAmountLessThenDebt;
use App\Http\Requests\ReplenishmentRequest;
use App\Rules\CheckIfAmountLesThenRecharge;
use App\Services\SendMoneys\SendMoneyStoreService;
use App\Services\SendMoneys\SendMoneyConfirmService;

class SendMoneyController extends Controller
{
    protected $sendMoneyStoreService;
    protected $sendMoneyConfirmService;
    private $currMonth;
    private $currYear;

    public function __construct(SendMoneyStoreService $sendMoneyStoreService, SendMoneyConfirmService $sendMoneyConfirmService)
    {
        $this->sendMoneyStoreService = $sendMoneyStoreService;
        $this->sendMoneyConfirmService = $sendMoneyConfirmService;
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
            // DB::table('send_money')
            $sendMoney = SendMoney::leftJoin('users as receiver', 'send_money.receiver_id', '=', 'receiver.id')
            ->leftJoin('users as sender', 'send_money.sender_id', '=', 'sender.id')
            ->leftJoin('users as envoy', 'send_money.envoy_id', '=', 'envoy.id')
            ->select(
                'send_money.*',
                // Receiver infos
                'receiver.id as receiver_id',
                'receiver.code as receiver_code',
                'receiver.first_name as receiver_first_name',
                'receiver.last_name as receiver_last_name',
                'receiver.email as receiver_email',
                //Sender infos
                'sender.id as sender_id',
                'sender.code as sender_code',
                'sender.first_name as sender_first_name',
                'sender.last_name as sender_last_name',
                'sender.email as sender_email',
                //Envoy infos
                'envoy.id as envoy_id',
                'envoy.code as envoy_code',
                'envoy.first_name as envoy_first_name',
                'envoy.last_name as envoy_last_name',
                'envoy.email as envoy_email',
            )->orderBy('created_at', 'DESC')->paginate(50);

            $sendMoneyTotal = SendMoney::leftJoin('users as receiver', 'send_money.receiver_id', '=', 'receiver.id')
            ->leftJoin('users as sender', 'send_money.sender_id', '=', 'sender.id')
            ->leftJoin('users as envoy', 'send_money.envoy_id', '=', 'envoy.id')
            ->where('send_money.status', '=', 'confirmed')
            ->sum('send_money.amount');

            return response()->json(['sendMoneys' => $sendMoney, 'sendMoneyTotal' => $sendMoneyTotal], 200);
        }
        if (Auth::user()->hasRole('agent')) {
            $sendMoney = SendMoney::leftJoin('users as receiver', 'send_money.receiver_id', '=', 'receiver.id')
                ->leftJoin('users as sender', 'send_money.sender_id', '=', 'sender.id')
                ->leftJoin('users as envoy', 'send_money.envoy_id', '=', 'envoy.id')
                ->where('send_money.receiver_id', '=', Auth::id())
                ->select(
                    'send_money.*',
                    // Receiver infos
                    'receiver.id as receiver_id',
                    'receiver.code as receiver_code',
                    'receiver.first_name as receiver_first_name',
                    'receiver.last_name as receiver_last_name',
                    'receiver.email as receiver_email',
                    //Sender infos
                    'sender.id as sender_id',
                    'sender.code as sender_code',
                    'sender.first_name as sender_first_name',
                    'sender.last_name as sender_last_name',
                    'sender.email as sender_email',
                    //Envoy infos
                    'envoy.id as envoy_id',
                    'envoy.code as envoy_code',
                    'envoy.first_name as envoy_first_name',
                    'envoy.last_name as envoy_last_name',
                    'envoy.email as envoy_email',
                )->orderBy('created_at', 'DESC')->paginate(50);

                $sendMoneyTotal = SendMoney::leftJoin('users as receiver', 'send_money.receiver_id', '=', 'receiver.id')
                ->leftJoin('users as sender', 'send_money.sender_id', '=', 'sender.id')
                ->leftJoin('users as envoy', 'send_money.envoy_id', '=', 'envoy.id')
                ->where('send_money.receiver_id', '=', Auth::id())
                ->where('send_money.status', '=', 'confirmed')
                ->sum('send_money.amount');

            return response()->json(['sendMoneys' => $sendMoney, 'sendMoneyTotal' => $sendMoneyTotal], 200);
        }
        if (Auth::user()->hasRole('envoy')) {
            $sendMoney = SendMoney::leftJoin('users as receiver', 'send_money.receiver_id', '=', 'receiver.id')
            ->leftJoin('users as sender', 'send_money.sender_id', '=', 'sender.id')
            ->leftJoin('users as envoy', 'send_money.envoy_id', '=', 'envoy.id')
            ->where('send_money.envoy_id', '=', Auth::id())
            ->select(
                'send_money.*',
                // Receiver infos
                'receiver.id as receiver_id',
                'receiver.code as receiver_code',
                'receiver.first_name as receiver_first_name',
                'receiver.last_name as receiver_last_name',
                'receiver.email as receiver_email',
                //Sender infos
                'sender.id as sender_id',
                'sender.code as sender_code',
                'sender.first_name as sender_first_name',
                'sender.last_name as sender_last_name',
                'sender.email as sender_email',
                //Envoy infos
                'envoy.id as envoy_id',
                'envoy.code as envoy_code',
                'envoy.first_name as envoy_first_name',
                'envoy.last_name as envoy_last_name',
                'envoy.email as envoy_email',
            )->orderBy('created_at', 'DESC')->paginate(50);
        
            $sendMoneyTotal = SendMoney::leftJoin('users as receiver', 'send_money.receiver_id', '=', 'receiver.id')
            ->leftJoin('users as sender', 'send_money.sender_id', '=', 'sender.id')
            ->leftJoin('users as envoy', 'send_money.envoy_id', '=', 'envoy.id')
            ->where('send_money.envoy_id', '=', Auth::id())
            ->where('send_money.status', '=', 'confirmed')
            ->sum('send_money.amount');

            return response()->json(['sendMoneys' => $sendMoney, 'sendMoneyTotal' => $sendMoneyTotal], 200);
        }
        if (Auth::user()->hasRole('system-engineer')) {
            // DB::table('send_money')
            $sendMoney = SendMoney::leftJoin('users as receiver', 'send_money.receiver_id', '=', 'receiver.id')
            ->leftJoin('users as sender', 'send_money.sender_id', '=', 'sender.id')
            ->leftJoin('users as envoy', 'send_money.envoy_id', '=', 'envoy.id')
            ->select(
                'send_money.*',
                // Receiver infos
                'receiver.id as receiver_id',
                'receiver.code as receiver_code',
                'receiver.first_name as receiver_first_name',
                'receiver.last_name as receiver_last_name',
                'receiver.email as receiver_email',
                //Sender infos
                'sender.id as sender_id',
                'sender.code as sender_code',
                'sender.first_name as sender_first_name',
                'sender.last_name as sender_last_name',
                'sender.email as sender_email',
                //Envoy infos
                'envoy.id as envoy_id',
                'envoy.code as envoy_code',
                'envoy.first_name as envoy_first_name',
                'envoy.last_name as envoy_last_name',
                'envoy.email as envoy_email',
            )->orderBy('created_at', 'DESC')->paginate(50);

            $sendMoneyTotal = SendMoney::leftJoin('users as receiver', 'send_money.receiver_id', '=', 'receiver.id')
            ->leftJoin('users as sender', 'send_money.sender_id', '=', 'sender.id')
            ->leftJoin('users as envoy', 'send_money.envoy_id', '=', 'envoy.id')
            ->where('send_money.status', '=', 'confirmed')
            ->sum('send_money.amount');

            return response()->json(['sendMoneys' => $sendMoney, 'sendMoneyTotal' => $sendMoneyTotal], 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SendMoneyRequest $request)
    {
        $agent = User::with(['user_account', 'user_bank_accounts'])->findOrFail($request->agent_id);
        $envoy = User::with(['user_account', 'user_bank_accounts'])->findOrFail($request->envoy_id);
        $systemAccount = SystemAccount::first();
        $systemBankAccount = SystemBankAccount::first();

        return $this->sendMoneyStoreService->sendMoneyStore($request, $agent, $envoy, $systemAccount, $systemBankAccount);

        return response()->json(["message" => "Money send registred successfully!"], 200);
    }

    public function confirmSendMoney(Request $request)
    {
        $sendmoney = SendMoney::findOrFail($request->send_money_id);
        $receiver = User::with(['user_account', 'user_bank_accounts'])->findOrFail($request->agent_id);
        $envoy = User::with(['user_account'])->findOrFail($sendmoney->envoy_id);

        return $this->sendMoneyConfirmService->sendMoneyConfirm($request, $receiver, $envoy, $sendmoney);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SendMoney  $sendMoney
     * @return \Illuminate\Http\Response
     */
    public function show(SendMoney $sendMoney)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SendMoney  $sendMoney
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SendMoney $sendMoney)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SendMoney  $sendMoney
     * @return \Illuminate\Http\Response
     */
    public function destroy(SendMoney $sendMoney)
    {
        //
    }
}
