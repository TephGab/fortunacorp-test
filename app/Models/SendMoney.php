<?php

namespace App\Models;

use App\Models\User;
use App\Models\SendMoneyActivit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SendMoney extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'status', 
        'amount', 
        'currency',
        'system_bank_account_id', 
        'system_account_id',
        'user_bank_account_id', 
        'user_account_id',
        'sender_id', 'receiver_id', 
        'confirmed_by_receiver', 
        'confirmed_by_envoy', 
        'initialization_date', 
        'receiver_confirmation_date',
        'envoy_confirmation_date',
    ];

    public function receiver() {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function sender() {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function envoy() {
        return $this->belongsTo(User::class, 'envoy_id');
    }

    public function send_money_activits(){
        return $this->hasMany(SendMoneyActivit::class);
    }

}