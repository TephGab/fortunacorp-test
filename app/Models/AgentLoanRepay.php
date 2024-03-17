<?php

namespace App\Models;

use App\Models\User;
use App\Models\AgentLoanRepayActivit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AgentLoanRepay extends Model
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

        public function user()
        {
            return $this->belongsTo(User::class, 'user_id');
        }
    
        public function admin(){
            return $this->belongsTo(User::class, 'receiver_id');
        }

        public function envoy(){
            return $this->belongsTo(User::class, 'envoy_id');
        }
    
        public function agent_loan_repay_activits(){
            return $this->hasMany(AgentLoanRepayActivit::class);
        }
}
