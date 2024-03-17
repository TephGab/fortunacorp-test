<?php

namespace App\Models;

use App\Models\User;
use App\Models\AgentLoanActivit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AgentLoan extends Model
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

    public function admin()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function receiver(){
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function agent_loan_activits(){
        return $this->hasMany(AgentLoanActivit::class);
    }
}