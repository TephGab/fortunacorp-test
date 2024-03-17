<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use WhichBrowser\Parser;
use App\Models\UserVisit;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\UserActiveSession;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Events\UserOnlineStatusUpdated;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    protected function authenticated(Request $request, $user)
    {
        if ($user->hasRole('operator')) {
            //Retreive pending transactions
            $transactions = Transaction::where('operator_id', 1)->where('status', 'pending')->get();
            if($transactions){
                foreach ($transactions as $transaction) { 
                    $transaction->update(['operator_id' => $user->id]);
                }
            }
        }
        // Set the user's online status as 'online' in the database upon successful login
        $user->update(['online_status' => 'online']);

        $ipAddress = $request->ip();
        $userAgent = $request->header('User-Agent');
        $deviceType = strpos($userAgent, 'Mobi') !== false ? 'phone' : 'desktop';

        $parser = new Parser($userAgent);

        // Get detailed browser information
        $browser = $parser->browser->getName();
        $browserVersion = $parser->browser->getVersion();
        $engine = $parser->engine->getName();
        $os = $parser->os->getName();
        $osVersion = $parser->os->getVersion();
        
        UserVisit::create([
            'user_id' => Auth::id(),
            'ip_address' => $ipAddress,
            'session_id' => $request->session()->getId(),
            'login_time' => now(),
            'user_agent' => $userAgent,
            'device_type' => $deviceType,
            'browser_name' => $browser,
            'browser_version' => $browserVersion,
            'engine' => $engine,
            'os' => $os,
            'os_version' => $osVersion,
        ]);

        // Use Pusher to broadcast the online status to the client-side (Vue.js)
        event(new UserOnlineStatusUpdated($user->id, 'online'));
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        // Set the user's online status as 'offline' in the database upon logout
        $user->update(['online_status' => 'offline']);
        
        UserVisit::where('user_id', $user->id)->delete();

        // Use Pusher to broadcast the online status to the client-side (Vue.js)
        event(new UserOnlineStatusUpdated($user->id, 'offline'));

        //Retreive operator transactions
        $transactions = Transaction::where('operator_id', $user->id)->where('status', 'pending')->get();

        if($transactions){
            foreach ($transactions as $transaction) { 
                //Retreive Operator
                $operator = User::leftJoin('transactions', function ($join) {
                    $join->on('users.id', '=', 'transactions.operator_id')
                    ->where('transactions.operator_id', '!=', null);
                })->select('users.id')
                    ->where('online_status', '=', 'online')
                    ->orWhere(function ($query) {
                    $query->groupBy('users.id')->havingRaw('COUNT(transactions.id) >= 0');
                })->whereHas('roles', function ($query) {
                    $query->where('name', 'operator');
                })->orderByRaw('(SELECT COUNT(transactions.id) FROM transactions WHERE transactions.operator_id = users.id AND transactions.status = "pending") ASC')->first();
                
                if($operator){
                    $transaction->update(['operator_id' => $operator->id]);
                }else{
                    $transaction->update(['operator_id' => 1]);
                }
            }
        }
        // Log the user out
        Auth::logout();
        return redirect('/'); // Replace with your desired redirection URL after logout.
    }
}