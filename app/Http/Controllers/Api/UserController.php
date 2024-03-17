<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
// use GuzzleHttp\Client;
// use Twilio\Rest\Client;
use App\Models\UserVisit;
use App\Models\UserAccount;
use Twilio\Http\CurlClient;
use Vonage\SMS\Message\SMS;
use Illuminate\Http\Request;
use App\Mail\WelcomeUserMail;
// use Twilio\Http\GuzzleClient;
use App\Models\UserBankAccount;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Jobs\SendWelcomeUserSmsJob;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
// use Vonage\Client\Credentials\Basic;
use App\Http\Resources\UserResource;
use App\Jobs\SendWelcomeUserMailJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Vonage\Client\Credentials\Basic;
use App\Http\Requests\UserFormRequest;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Database\QueryException;
/////
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use App\Models\TransfertProfitToRecharge;
use GuzzleHttp\Exception\RequestException;
use App\Http\Requests\UpdateUserFormRequest;
use App\Http\Requests\resetPasswordFormRequest;
use App\Http\Requests\UpdatePasswordFormRequest;
use App\Rules\CheckIfTransfertLessThenUserProfits;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // Fetch the user by ID
        //$user = User::findOrFail(149);

        // Get the referrals for the user
        //$referrals = $user->referredBy;

        // Return the referrals or do something else with them
        //return response()->json(['referrals' => $referrals]);
        // $user = User::where('email', 'tephgab@gmail.com')->first();    
        // SendWelcomeUserMailJob::dispatch($user);
        // if (Auth::user()->hasRole('super-admin')) {
        //     return User::with([
        //         'user_account', 'department', 'user_bank_accounts', 'roles', 'permissions',
        //         'user_visits' => function ($query) {
        //             $query->latest('created_at')->first();
        //         }
        //     ])->orderBy('created_at', 'DESC')->paginate(50);
        // }else{
        return User::with([
            'user_account', 'department', 'user_bank_accounts', 'roles', 'permissions',
            'user_visits' => function ($query) {
                $query->latest('created_at')->first();
            }
        ])->whereRelation('roles', 'name', '!=', 'super-admin')->orderBy('created_at', 'DESC')->paginate(50);
        //}
    }

    public function search()
    {
        if (request('q') != null) {
            return User::with([
                'user_account', 'department', 'user_bank_accounts', 'roles', 'permissions',
                'user_visits' => function ($query) {
                    $query->latest('created_at')->first();
                }
            ])->where('first_name', 'like', '%' . request('q') . '%')->orWhere('last_name', 'like', '%' . request('q') . '%')->orderBy('created_at', 'DESC')->paginate(50);
            //return Transaction::with(['client'])->whereRelation('client','name', 'like', '%' . request('q') . '%')->orderBy('created_at', 'DESC')->paginate(6);
        }
    }

    public function generatePdf(Request $request)
    {
        if ($request->pdf_export_option == 'agent') {
            return User::with([
                'user_account', 'department', 'user_bank_accounts', 'roles', 'permissions',
                'user_visits' => function ($query) {
                    $query->latest('created_at')->first();
                }
            ])->role('agent')->orderBy('created_at', 'DESC')->get();
        }
        if ($request->pdf_export_option == 'operator') {
            return User::with([
                'user_account', 'department', 'user_bank_accounts', 'roles', 'permissions',
                'user_visits' => function ($query) {
                    $query->latest('created_at')->first();
                }
            ])->role('operator')->orderBy('created_at', 'DESC')->get();
        }
        if ($request->pdf_export_option == 'admin') {
            return User::with([
                'user_account', 'department', 'user_bank_accounts', 'roles', 'permissions',
                'user_visits' => function ($query) {
                    $query->latest('created_at')->first();
                }
            ])->role('admin')->orderBy('created_at', 'DESC')->get();
        }
        if ($request->pdf_export_option == 'system_engineer') {
            return User::with([
                'user_account', 'department', 'user_bank_accounts', 'roles', 'permissions',
                'user_visits' => function ($query) {
                    $query->latest('created_at')->first();
                }
            ])->role('system-engineer')->orderBy('created_at', 'DESC')->get();
        }
        if ($request->pdf_export_option == 'all') {
            return User::with([
                'user_account', 'department', 'user_bank_accounts', 'roles', 'permissions',
                'user_visits' => function ($query) {
                    $query->latest('created_at')->first();
                }
            ])->whereRelation('roles', 'name', '!=', 'super-admin')->orderBy('created_at', 'DESC')->get();
        }
    }

    public function searchReference()
    {
        return UserResource::collection(User::all());
    }

    public function getEnvoyAgentInfos(){
        if (Auth::user()->hasRole('envoy')) {
            return User::where('id', Auth::id())->with(['envoy_agents.user_account', 'envoy_agents.envoy'])->paginate(50);

            // return User::where('id', Auth::id())
            // ->with(['envoy_agents.user_account' => function ($query) {
            //     $query->orderBy('cash_in_hand')
            //           ->orderBy('capital')
            //           ->orderBy('investments');
            // }])->paginate(50);
        }
        if (Auth::user()->hasRole('system-engineer') || Auth::user()->hasRole('super-admin') || Auth::user()->hasRole('admin')) {
            return User::with(['envoy_agents.user_account', 'envoy_agents.envoy'])->paginate(50);

            // return User::with(['envoy_agents.user_account, envoy_agents.envoy' => function ($query) {
            //     $query->orderBy('cash_in_hand')
            //           ->orderBy('capital')
            //           ->orderBy('investments');
            // }])->paginate(50);
        }
    }

    public function getSelectedEnvoyAgentInfos(Request $request){
        return User::where('id', $request->envoy_id)->with(['envoy_agents.user_account', 'envoy_agents.envoy'])->paginate(50);
        // return User::where('id', $request->envoy_id)
        // ->with(['envoy_agents.user_account' => function ($query) {
        //     $query->orderBy('cash_in_hand')
        //           ->orderBy('capital')
        //           ->orderBy('investments');
        // }])->paginate(50);
    }

    public function updateEnvoyAgent(Request $request){
        $agent = User::findOrFail($request->agent_id);
        $agent->update(['envoy_id' => $request->envoy_id]);
        return response()->json(['message' => 'agent envoy updated']);
    }

    public function getEnvoys()
    {
        return UserResource::collection(User::with(['roles', 'permissions'])->whereRelation('roles', 'name', '=', 'envoy')->get());
    }

    public function getEnvoyList(){
        return User::role('envoy')->with(['user_account'])->get();
    }

    public function getAgents()
    {
        return UserResource::collection(User::with(['roles', 'permissions'])->whereRelation('roles', 'name', '=', 'agent')->get());
    }

    public function getAgentList(Request $request)
    {
        if ($request->agent_id) {
            return User::with(['roles', 'permissions', 'envoy'])->where('id', $request->agent_id)->whereRelation('roles', 'name', '=', 'agent')->get();
        }else{
            return User::with(['roles', 'permissions', 'envoy'])->where('id', '!=', $request->agent_id)->whereRelation('roles', 'name', '=', 'agent')->get();
        }
    }

    public function getSelectedAgentList(Request $request)
    {
        return User::with(['roles', 'permissions'])->where('id', $request->agent_id)->whereRelation('roles', 'name', '=', 'agent')->get();
    }

    public function getOperators()
    {
        return UserResource::collection(User::with(['roles', 'permissions'])->whereRelation('roles', 'name', '=', 'operator')->get());
    }

    public function getAgent(Request $request)
    {
        return User::with(['roles', 'permissions', 'user_account', 'user_bank_accounts'])->where('id', $request->agent_id)->get();
    }

    public function getReference(Request $request)
    {
        return User::with(['roles', 'permissions'])->where('id', $request->id)->get();
    }

    public function getUserAccount(Request $request)
    {
        $user = User::with(['user_account'])->findOrFail($request->id);
        return UserAccount::findOrFail($user->user_account->id);
    }

    public function getRoles()
    {
        // return Role::with(['permissions'])->where('name', '!=', 'super-admin')->get();
        return Role::with(['permissions'])->get();
    }

    public function getPermissions()
    {
        return Permission::get();
    }

    public function getAuthUser()
    {
        return User::with('roles', 'permissions')->where('id', Auth::id())->first();
    }

    public function getAuthUserRole(Request $request)
    {
        $user = Auth::user();
        // Get the user's role name
        return $user->roles->pluck('name')->first();
    }

    public function sortUserByRole(Request $request)
    {
        if ($request->role == 'all') {
            return User::with([
                'user_account', 'department', 'user_bank_accounts', 'roles', 'permissions',
                'user_visits' => function ($query) {
                    $query->latest('created_at')->first();
                }
            ])->whereRelation('roles', 'name', '!=', 'super-admin')->orderBy('created_at', 'DESC')->paginate(50);
        } else {
            return User::with([
                'user_account',
                'department',
                'user_bank_accounts',
                'roles', // Include roles relation
                'permissions',
                'user_visits' => function ($query) {
                    $query->latest('created_at')->first();
                }
            ])->whereHas('roles', function ($query) use ($request) {
                // Filter for users with the "admin" role
                $query->where('name', $request->role);
            })->orderBy('created_at', 'DESC')->paginate(50);
        }
    }

    public function sortByBrdr(Request $request)
    {
        if ($request->role == 'all') {
            return User::with([
                'user_account', 'department', 'user_bank_accounts', 'roles', 'permissions',
                'user_visits' => function ($query) {
                    $query->latest('created_at')->first();
                }
            ])->whereRelation('roles', 'name', '!=', 'super-admin')->orderBy('created_at', 'DESC')->paginate(50);
        } else {
            return User::with([
                'user_account',
                'department',
                'user_bank_accounts',
                'roles',
                'permissions',
                'user_visits' => function ($query) {
                    $query->latest('created_at')->first();
                }
            ])
                ->whereRelation('roles', 'name', '!=', 'super-admin')
                ->orderByDesc(function ($query) use ($request) {
                    $query->select($request->brdr_name)
                        ->from('user_accounts')
                        ->whereColumn('user_accounts.user_id', 'users.id')
                        ->orderBy('balance', 'DESC')
                        ->limit(1);
                })
                ->paginate(50);
        }
    }

    public function getAuthUserProfile()
    {
        $user = User::with(['user_account', 'department', 'user_bank_accounts', 'roles', 'permissions'])->findOrFail(Auth::id());
        //$user = User::with(['user_account', 'user_bank_accounts' ,'roles', 'permissions'])->where('id', Auth::id())->get();
        $userAccount = UserAccount::findOrFail($user->user_account->id);
        return ['user' => $user, 'userAccount' => $userAccount];
    }

    public function geCurrentPermissionsOnUserEdit(Request $request)
    {
        $user = User::findOrFail($request->user_id);

        if ($user) {
            $role = Role::find($request->role_id);

            if ($user->hasRole($role->name)) {
                return $role->permissions()->whereHas('users', function ($query) use ($user) {
                    $query->where('users.id', $user->id);
                })->get();
            }
        } else {
            return 'no_permission';
        }
    }

    public function getAvailablePermissionsOnUserEdit(Request $request)
    {
        $user = User::findOrFail($request->user_id);

        if ($user) {
            $role = Role::find($request->role_id);

            return $role->permissions()->whereDoesntHave('users', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            })->get();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserFormRequest $request)
    {
        $user = new User;
        if (request()->file('avatar')) {
            $photo = request()->file('avatar');
            $imageName = $photo->getClientOriginalName();
            $imageName = time() . '_' . $imageName;
            // $photo->move(public_path('/img/users'), $imageName);
            $storedPath = Storage::putFileAs('img/users', $photo, $imageName);
            $user->avatar = $imageName;
        }
        $user->code = $request->code;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->sex = $request->sex;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->password = Hash::make($request->password);
        $user->address = $request->address;
        $user->location = $request->location;
        $user->limit_min = $request->limit_min;
        $user->limit_max = $request->limit_max;
        $user->percentage = $request->percentage;
        $user->reference = $request->reference;
        $user->id_type = $request->id_type;
        $user->id_number = $request->id_number;
        $user->id_picture = $request->id_picture;
        $user->department_id = $request->department_id;
        $user->registered_by = Auth::id();
        $user->save();

        $useraccount = new UserAccount;
        $useraccount->balance = 0;
        $useraccount->depts = 0;
        $useraccount->withdrawal = 0;
        $useraccount->category = 'none';
        $useraccount->user_id = $user->id;
        $useraccount->save();

        if (count($request->input('bankAccounts')) != 0) {
            $bankAccounts = $request->input('bankAccounts');
            foreach ($bankAccounts as $bankAccaount) {
                $userbankaccount = new UserBankAccount;
                $userbankaccount->account_name = $bankAccaount['account_name'];
                $userbankaccount->account_number = $bankAccaount['account_number'];
                $userbankaccount->balance = $bankAccaount['account_balance'];
                $userbankaccount->currency = $bankAccaount['currency'];
                $userbankaccount->bank_name = $bankAccaount['bank_name'];
                $userbankaccount->category = 'none';
                $userbankaccount->user_id = $user->id;
                $userbankaccount->save();
            }
        }

        $role = Role::select('id')->where('name', $request->role)->first();
        $user->roles()->attach($role);
        //$user->assignRole($role);

        // Assign roles and permissions
        $permissions = Permission::whereIn('name', $request->permissions)->get();
        $user->givePermissionTo($permissions);

        // $reference = User::findOrFail($request->reference);
        // if($reference->godson != null){
        //     $ref = array($user->toArray(), $reference->godson);
        //$ref = array_push($reference->godson, $user->toArray());
        // $ref[] = array_merge($user->toArray(), $reference->godson);
        //     $reference->update(['godson' => $ref]);
        //     return $ref;
        // }else{
        //     $reference->update(['godson' => $user->toArray()]);
        // }

        // Send welcome email / SMS
        SendWelcomeUserMailJob::dispatch($user);
         // SendWelcomeUserSmsJob::dispatch($user);
        // End send welcome mail / SMS

        return response()->json($user);
    }


    public function transfertProfitsToRecharge(Request $request)
    {
        $laravelTime = config('app.timezone');
        Carbon::setTestNow(Carbon::now($laravelTime));

        $agent = User::with(['user_account'])->findOrFail($request->user_id);

        $request->validate([
            'profits_transfert' => [
                'required',
                new CheckIfTransfertLessThenUserProfits($request->user_commission)
            ],
        ]);
       
        
        $current_balance = $request->user_commission;
        $new_balance = ($current_balance - $request->profits_transfert);

        $current_depts = $agent->user_account->depts;
        $new_depts = $agent->user_account->depts;

        $current_investment = $agent->user_account->investments;
        $new_investment = ($request->profits_transfert +  $agent->user_account->investments);

        try {
            DB::beginTransaction();
            $invest = new TransfertProfitToRecharge;
            $invest->amount = $request->profits_transfert;
            $invest->current_balance = $current_balance;
            $invest->new_balance = $new_balance;
            $invest->current_depts = $current_depts;
            $invest->new_depts = $new_depts;
            $invest->current_investment = $current_investment;
            $invest->new_investment = $new_investment;
            $invest->user_id = $request->user_id;
            $invest->completed_date = Carbon::now($laravelTime);
            $invest->commission_category = $request->commission_category;
            $invest->save();
            DB::commit();

            try {
                DB::beginTransaction();
                $invest->commission_category == 'commission' ? $agent->user_account->update(['profits' => $invest->new_balance]) : $agent->user_account->update(['referral_commissions' => $invest->new_balance]);
                $agent->user_account->update(['investments' => $invest->new_investment]);
                $agent->save();    
                DB::commit();
                return response()->json(["message" => "Transfer success"], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['error' => 'An error occurred during transfer process. Please try again. Error: ' . $e->getMessage()], 500);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'An error occurred during transfer process. Please try again. Error: ' . $e->getMessage()], 500);
        }
    }


    public function allowAndDisallowAgentDebt(Request $request)
    {
        $agent = User::findOrFail($request->agent_id);

        switch ($agent) {
            case $agent->dept_allowed == true:
                $agent->update(['dept_allowed' => false]);
                break;
            default:
                $agent->update(['dept_allowed' => true]);
                break;
        }

        return User::with([
            'user_account', 'department', 'user_bank_accounts', 'roles', 'permissions',
            'user_visits' => function ($query) {
                $query->latest('created_at')->first();
            }
        ])->whereRelation('roles', 'name', '!=', 'super-admin')->orderBy('created_at', 'DESC')->paginate(50);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $user = User::with([
            'user_account', 'user_bank_accounts', 'roles', 'permissions', 'department',
            'user_visits' => function ($query) {
                $query->latest('created_at')->get();
            },
            'referredBy',
            'referrals'
        ])->findOrFail($user->id);
        return response()->json($user);
        // $contextName = 'user_activities';

        // $user = User::with([
        //     'user_account', 'user_bank_accounts', 'roles', 'permissions', 'department',
        //     'user_visits' => function ($query) {
        //         $query->latest('created_at')->get();
        //     },
        //     'userSorts' => function ($query) use ($contextName) {
        //         $query->whereHas('contextSorts', function ($q) use ($contextName) {
        //             $q->where('context', $contextName);
        //         })->latest('created_at')->first();
        //     }
        // ])->findOrFail($user->id);
        // return response()->json($user);
    }

    public function getUserSorts(Request $request)
    {
        $user_id = $request->user_id;
        $contextName = 'user_activities';
        $selectedYear = $request->selected_year;
        $selectedMonth = $request->selected_month; 

        $user = User::with([
            'user_account', 
            'user_bank_accounts', 
            'roles', 
            'permissions', 
            'department',
            'user_visits' => function ($query) {
                $query->latest('created_at')->get();
            },
            'userSorts' => function ($query) use ($contextName, $selectedYear, $selectedMonth) {
                $query->whereHas('contextSorts', function ($q) use ($contextName, $selectedYear, $selectedMonth) {
                    $q->where('context', $contextName);
                        // ->where('selected_year', '=', $selectedYear)
                        // ->where('selected_month', '=', $selectedMonth);
                })->latest('updated_at')->first();
            }
        ])->findOrFail($user_id);

        $user->load(['userSorts' => function ($query) use ($contextName, $selectedYear, $selectedMonth) {
            $query->whereHas('contextSorts', function ($q) use ($contextName, $selectedYear, $selectedMonth) {
                $q->where('context', $contextName);
                    // ->where('selected_year', '=', $selectedYear)
                    // ->where('selected_month', '=', $selectedMonth);
            })->latest('updated_at')->limit(1);
        }]);
        

        // $user = User::with([
        //     'user_account', 'user_bank_accounts', 'roles', 'permissions', 'department',
        //     'user_visits' => function ($query) {
        //         $query->latest('created_at')->get();
        //     },
        //     'userSorts' => function ($query) use ($contextName, $selectedYear, $selectedMonth) {
        //         $query->whereHas('contextSorts', function ($q) use ($contextName, $selectedYear, $selectedMonth) {
        //             $q->where('context', $contextName)
        //                 ->where('selected_year', '=', $selectedYear)
        //                 ->where('selected_month', '=', $selectedMonth)->latest('updated_at')->first();
        //         })->latest('updated_at')->first();
        //     }
        // ])->findOrFail($user_id);

        return response()->json($user);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserFormRequest $request, User $user)
    {
        // $validated = $request->validate([
        //     'permissions' => 'required|array',
        //     'permissions.*' => 'string|exists:permissions,name',
        // ]);

        $user = User::findOrFail($user->id);
      
        try {
            DB::beginTransaction();
                $user->update(['code' => $request->code,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'sex' => $request->sex,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'location' => $request->location,
                'default_percentage' => $request->default_percentage,
                'limit_min' => $request->limit_min,
                'limit_max' => $request->limit_max,
                'reference' => $request->reference,
                'department_id' => $request->department_id,
                'id_number' => $request->id_number,
                'id_type' => $request->id_type,
                ]);
            DB::commit();
        } catch (QueryException $exception) {
            DB::rollBack();
            Log::error('Error durring user update: ' . $exception->getMessage());
            return response()->json([
                'message' => 'Error occured durring user update!',
                'errors' => [
                    'user_update' => [
                        'Error occured durring user update! if the issue persist please contact system administrator.'
                    ]
                ]
            ], 400);
        }
        // $user->update(['code' => $request->code]);
        // $user->update(['first_name' => $request->first_name]);
        // $user->update(['last_name' => $request->last_name]);
        // $user->update(['email' => $request->email]);
        // $user->update(['phone' => $request->phone]);
        // $user->update(['address' => $request->address]);
        // $user->update(['location' => $request->location]);
        // $user->update(['percentage' => $request->percentage]);
        // $user->update(['limit_min' => $request->limit_min]);
        // $user->update(['limit_max' => $request->limit_max]);
        // $user->update(['reference' => $request->reference]);

        // $user->update(['department_id' => $request->department_id]);
        // $user->update(['id_number' => $request->id_number]);
        // $user->update(['id_type' => $request->id_type]);

        $role = Role::findById($request->role_id);
        //$user->revokePermissionTo($user->permissions);
        $user->roles()->detach();

        $user->assignRole($role->name);
        $user->givePermissionTo([$request->permissions]);

        if (count($request->input('bankAccounts')) != 0) {
            $bankAccounts = $request->input('bankAccounts');
            foreach ($bankAccounts as $bankAccount) {
                $userbankaccount = UserBankAccount::findOrFail($bankAccount['id']);
                $userbankaccount->update(['account_name' => $bankAccount['account_name']]);
                $userbankaccount->update(['account_number' => $bankAccount['account_number']]);
                $userbankaccount->update(['balance' => $bankAccount['balance']]);
                $userbankaccount->update(['currency' => $bankAccount['currency']]);
                $userbankaccount->update(['bank_name' => $bankAccount['bank_name']]);
                $userbankaccount->update(['category' => $bankAccount['category']]);
                $userbankaccount->update(['user_id' => $bankAccount['user_id']]);
                $userbankaccount->save();
            }
        }

        return User::with(['user_account', 'user_bank_accounts', 'roles', 'permissions'])->paginate(50);
    }

    public function removePermissionFromUser(Request $request)
    {
        $user = User::findOrFail($request->user_id);

        $user->revokePermissionTo($request->permission_name);

        return User::with(['user_account', 'user_bank_accounts', 'roles', 'permissions'])->paginate(50);
    }

    public function getProfitToRecharge()
    {
        if (Auth::user()->hasRole('agent')) {
            $transferts = TransfertProfitToRecharge::where('user_id', Auth::id())->orderBy('created_at', 'DESC')->paginate(50);
            $transfertTotal = TransfertProfitToRecharge::where('user_id', Auth::id())->sum('amount');
            return response()->json(['transferts' => $transferts , 'transfertTotal' => $transfertTotal]);
        }
        if (Auth::user()->hasRole('super-admin')) {
            $transferts =  TransfertProfitToRecharge::orderBy('created_at', 'DESC')->paginate(50);
            $transfertTotal = TransfertProfitToRecharge::sum('amount');
            return response()->json(['transferts' => $transferts , 'transfertTotal' => $transfertTotal]);
        }
        if (Auth::user()->hasRole('admin')) {
            $transferts = TransfertProfitToRecharge::orderBy('created_at', 'DESC')->paginate(50);
            $transfertTotal = TransfertProfitToRecharge::sum('amount');
            return response()->json(['transferts' => $transferts , 'transfertTotal' => $transfertTotal]);
        }
        if (Auth::user()->hasRole('system-engineer')) {
            $transferts = TransfertProfitToRecharge::orderBy('created_at', 'DESC')->paginate(50);
            $transfertTotal = TransfertProfitToRecharge::sum('amount');
            return response()->json(['transferts' => $transferts , 'transfertTotal' => $transfertTotal]);
        }
    }

    public function resetPassword(resetPasswordFormRequest $request)
    {
        $user = User::findOrFail($request->user_id);
        $user->update(['password' => Hash::make($request->new_password)]);

        return User::with(['user_account', 'user_bank_accounts', 'roles', 'permissions'])->paginate(50);
    }

    public function updatePassword(UpdatePasswordFormRequest $request, User $user)
    {
        $user = User::findOrFail(Auth::user()->id);
        $user->update(['password' => Hash::make($request->new_password)]);

        return User::with(['user_account', 'user_bank_accounts', 'roles', 'permissions'])->paginate(50);
    }

    public function logoutOtherSessions(Request $request)
    {
        $user = Auth::user();
        $currentSessionId = $request->session()->getId();

        // Get the session path
        $sessionPath = storage_path('framework/sessions');

        // Delete all active sessions except the current one
        $deletedSessions = UserVisit::where('user_id', $user->id)
            ->where('session_id', '<>', $currentSessionId)
            ->get();

        foreach ($deletedSessions as $deletedSession) {
            // Delete the session record from the database
            $deletedSession->delete();

            // Delete the session file from the file system
            $sessionFile = "$sessionPath/{$deletedSession->session_id}";
            if (File::exists($sessionFile)) {
                File::delete($sessionFile);
            }
        }

        // Optionally, invalidate the current session as well
        //Session::invalidate();

        // Redirect to login or another page
        //return redirect()->route('login');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user = User::findOrFail($user->id);
        User::destroy($user->id);
        return User::with(['user_account', 'user_bank_accounts', 'roles', 'permissions'])->paginate(50);
    }
}