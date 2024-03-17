<?php

namespace App\Models;

use App\Models\User;
use App\Models\AccountTransfertActivit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountTransfert extends Model
{
    use HasFactory;

    protected $fillable = ['amount','account_sender_id','account_receiver_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
  
    public function sender_account(){
        return $this->belongsTo(Account::class, 'account_sender_id');
    }

    public function receiver_account(){
        return $this->belongsTo(Account::class, 'account_receiver_id');
    }

    public function account_transfert_activits(){
        return $this->hasMany(AccountTransfertActivit::class);
    }
}
