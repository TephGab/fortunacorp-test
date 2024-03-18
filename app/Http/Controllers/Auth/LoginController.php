<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Jobs\AssignTransactions;
use App\Jobs\DispatchTransactions;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected function authenticated(Request $request, $user)
    {
        if ($user->hasRole('operator')) {
            AssignTransactions::dispatch($user->id);
        }
        // Set the user's online status as 'online' in the database upon successful login
        $user->update(['online_status' => 'online']);

        // $ipAddress = $request->ip();
        // $userAgent = $request->header('User-Agent');
        // $deviceType = strpos($userAgent, 'Mobi') !== false ? 'phone' : 'desktop';

        // $parser = new Parser($userAgent);

        // // Get detailed browser information
        // $browser = $parser->browser->getName();
        // $browserVersion = $parser->browser->getVersion();
        // $engine = $parser->engine->getName();
        // $os = $parser->os->getName();
        // $osVersion = $parser->os->getVersion();
        
        // UserVisit::create([
        //     'user_id' => Auth::id(),
        //     'ip_address' => $ipAddress,
        //     'session_id' => $request->session()->getId(),
        //     'login_time' => now(),
        //     'user_agent' => $userAgent,
        //     'device_type' => $deviceType,
        //     'browser_name' => $browser,
        //     'browser_version' => $browserVersion,
        //     'engine' => $engine,
        //     'os' => $os,
        //     'os_version' => $osVersion,
        // ]);

        // Use Pusher to broadcast the online status to the client-side (Vue.js)
        // event(new UserOnlineStatusUpdated($user->id, 'online'));
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
        
        // UserVisit::where('user_id', $user->id)->delete();

        // Use Pusher to broadcast the online status to the client-side (Vue.js)
        //event(new UserOnlineStatusUpdated($user->id, 'offline'));

        // Retrieve operator transactions
        // Dispatch job to assign transactions after logout
        if ($user->hasRole('operator')) {
            DispatchTransactions::dispatch($user->id);
        }
        // Log the user out
        Auth::logout();
        return redirect('/'); // Replace with your desired redirection URL after logout.
    }
}
