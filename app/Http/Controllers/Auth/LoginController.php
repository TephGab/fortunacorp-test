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

    protected function authenticated($user)
    {
        if ($user->hasRole('operator')) {
            AssignTransactions::dispatch($user->id);
        }
        // Set the user's online status as 'online' in the database upon successful login
        $user->update(['online_status' => 'online']);

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

        // Use Pusher to broadcast the online status to the client-side (Vue.js)
        //event(new UserOnlineStatusUpdated($user->id, 'offline'));

        // Dispatch job to assign transactions after logout
        if ($user->hasRole('operator')) {
            DispatchTransactions::dispatch($user->id);
        }
        // Log the user out
        Auth::logout();
        return redirect('/'); // Replace with your desired redirection URL after logout.
    }
}
