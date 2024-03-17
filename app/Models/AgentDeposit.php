<?php

namespace App\Models;

use App\Models\User;
use App\Models\AgentInvestment;
use App\Models\AgentDebtDeposit;
use App\Models\AgentDepositActivit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AgentDeposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount', 
        'status', 
        'type', 'currency', 
        'user_bank_account_id', 
        'system_bank_account_id', 
        'user_account_id', 
        'envoy_id', 
        'user_id', 
        'confirmed_by', 
        'comfirmation_date',
        'sender_id',
        'receiver_id', 
        'confirmed_by_receiver', 
        'confirmed_by_envoy', 
        'initialization_date', 
        'receiver_confirmation_date',
        'envoy_confirmation_date',
        'investments',
        'current_balance',
        'new_balance',
        'current_depts',
        'new_depts',
        'operation'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function envoy() {
        return $this->belongsTo(User::class, 'envoy_id');
    }

    public function admin() {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function agent_deposit_activits(){
        return $this->hasMany(AgentDepositActivit::class);
    }

    public function agent_investments(){
        return $this->hasMany(AgentInvestment::class);
    }

    public function agent__debt_deposits(){
        return $this->hasMany(AgentDebtDeposit::class);
    }

}
