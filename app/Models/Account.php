<?php

namespace App\Models;

use App\Models\User;
use App\Models\Cashin;
use App\Models\Transaction;
use App\Models\AccountTransfert;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends Model
{
    use HasFactory;

    protected $fillable = ['name','type','phone','category', 'balance', 'full_wallet', 'total_monthly_transactions', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cashins(){
        return $this->hasMany(Cashin::class);
    }

    public function operators()
    {
        return $this->belongsToMany(User::class, 'account_user', 'can_be_used_by', 'user_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function sentTransfers()
    {
        return $this->hasMany(AccountTransfert::class, 'account_sender_id');
    }

    public function receivedTransfers()
    {
        return $this->hasMany(AccountTransfert::class, 'account_receiver_id');
    }
}
