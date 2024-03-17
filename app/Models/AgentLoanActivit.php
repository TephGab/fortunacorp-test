<?php

namespace App\Models;

use App\Models\AgentLoan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AgentLoanActivit extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount', 
        'status', 
        'type',
        'currency',
        'confirmed_by_receiver',
        'receiver_confirmation_date',
        'comment'];

        public function agent_loan(){
            return $this->belongsTo(AgentLoan::class);
        }
}
