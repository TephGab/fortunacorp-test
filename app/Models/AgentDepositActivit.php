<?php

namespace App\Models;

use App\Models\AgentDeposit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AgentDepositActivit extends Model
{
    use HasFactory;

    public function agent_deposit(){
        return $this->belongsTo(AgentDeposit::class);
    }
}