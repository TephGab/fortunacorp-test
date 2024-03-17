<?php

namespace App\Models;

use App\Models\User;
use App\Models\Client;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransactionActivit extends Model
{
    use HasFactory;

    protected $fillable = ['current_agent_balance', 'new_agent_balance'];

    public function transaction(){
        return $this->belongsTo(Transaction::class);
    }

    public function client(){
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function receiver(){
        return $this->belongsTo(Client::class, 'receiver');
    }

    public function sender(){
        return $this->belongsTo(Client::class, 'sender');
    }
    
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function operator() {
        return $this->belongsTo(User::class, 'operator_id');
    }

    public function account() {
        return $this->belongsTo(Account::class, 'account_id');
    }

}