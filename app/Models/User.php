<?php

namespace App\Models;

use App\Models\Cashin;
use App\Models\Client;
use App\Models\Account;
use App\Models\Cashout;
use App\Models\UserSort;
use App\Models\AgentLoan;
use App\Models\SendMoney;
use App\Models\UserVisit;
use App\Models\Department;
use App\Models\ContextSort;
use App\Models\UserAccount;
use App\Models\AgentDeposit;
use App\Models\EnvoyDeposit;
use App\Models\SystemAccount;
use App\Models\AgentLoanRepay;
use App\Models\EnvoyTransfert;
use App\Models\ProviderPayment;
use App\Models\UserBankAccount;
use App\Models\AccountTransfert;
use App\Models\SystemBankAccount;
use Laravel\Sanctum\HasApiTokens;
use App\Models\TransactionComment;
use Spatie\Permission\Traits\HasRoles;
use App\Models\ReplenishmentRequirement;
use Illuminate\Notifications\Notifiable;
use App\Models\AgentLoanTransactionRepay;
use App\Models\TransfertProfitToRecharge;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'location',
        'solde',
        'owed',
        'limit_min',
        'limit_max',
        'percentage',
        'default_percentage',
        'loan_percentage',
        'password',
        'godson',
        'department_id',
        'id_number',
        'id_type',
        'online_status',
        'dept_allowed',
        'reference',
        'envoy_id',
        'sex',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'godson' => 'array',
    ];

    public function clients(){
        return $this->hasMany(Client::class);
    }

    public function transactions(){
        return $this->hasMany(Transaction::class);
    }

    public function cashin(){
        return $this->hasMany(Cashin::class);
    }

    public function cashouts(){
        return $this->hasMany(Cashout::class);
    }

    public function provider_payments(){
        return $this->hasMany(ProviderPayment::class);
    }

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    public function account_transferts()
    {
        return $this->belongsToMany(AccountTransfert::class);
    }

    public function transaction_comments(){
        return $this->hasMany(TransactionComment::class);
    }

    public function user_account(){
        return $this->hasOne(UserAccount::class);
    }

    public function user_bank_accounts(){
        return $this->hasMany(UserBankAccount::class);
    }

    public function system_account(){
        return $this->hasOne(SystemAccount::class);
    }

    public function system_bank_accounts(){
        return $this->hasMany(SystemBankAccount::class);
    }

    public function user_visits(){
        return $this->hasMany(UserVisit::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function replenishment_requirements(){
        return $this->hasMany(ReplenishmentRequirement::class);
    }

    public function agent_deposits(){
        return $this->hasMany(AgentDeposit::class);
    }

    public function envoy_deposits(){
        return $this->hasMany(EnvoyDeposit::class);
    }

    public function envoy_transferts(){
        return $this->hasMany(EnvoyTransfert::class);
    }

    public function withdraws(){
        return $this->hasMany(SendMoney::class, 'receiver_id');
    }

    public function profit_to_recharges(){
        return $this->hasMany(TransfertProfitToRecharge::class);
    }

    public function operator_accounts()
    {
        return $this->belongsToMany(Account::class, 'account_user', 'user_id', 'can_be_used_by');
    }

    public function receivesBroadcastNotificationsOn()
    {
        return 'App.User.'.$this->id;
    }

    public function userSorts()
    {
        return $this->belongsToMany(UserSort::class, 'context_sort_user_sort', 'user_id', 'user_sort_id')
            ->withTimestamps();
    }

    public function contextSorts()
    {
        return $this->belongsToMany(ContextSort::class, 'context_sort_user_sort', 'user_id', 'context_sort_id')
            ->withTimestamps();
    }

    public function referredBy()
    {
        return $this->belongsTo(User::class, 'reference');
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'reference');
    }

    public function operatorTransactions()
    {
        return $this->hasMany(Transaction::class, 'completed_by', 'id');
    }

    public function envoy_cashouts()
    {
        return $this->hasMany(Cashout::class, 'envoy_id', 'id');
    }

    public function agent_loans(){
        return $this->hasMany(AgentLoan::class, 'receiver_id');
    }

    public function agent_loan_repays(){
        return $this->hasMany(AgentLoanRepay::class, 'user_id');
    }

    public function agent_loan_transaction_repays(){
        return $this->hasMany(AgentLoanTransactionRepay::class, 'user_id');
    }

    public function envoy_agents()
    {
        return $this->hasMany(User::class, 'envoy_id')->whereHas('roles', function ($query) {
            $query->where('name', 'agent');
        });
    }

    public function envoy()
    {
        return $this->belongsTo(User::class, 'envoy_id');
    }
    // public function envoy_agents()
    // {
    //     return $this->hasMany(User::class, 'envoy_id')->where('role', 'agent');
    // }

}
