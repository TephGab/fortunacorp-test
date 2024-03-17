<?php

namespace App\Models;

use App\Models\AgentDeposit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AgentDebtDeposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'status', 
        'amount', 
        'currency',
        'user_id',
        'agent_deposit_id',
        'current_balance',
        'new_balance',
        'current_depts',
        'new_depts',
        'current_investment',
        'new_investment'
    ];

    public function agent_deposit(){
        return $this->belongsTo(AgentDeposit::class);
    }
}