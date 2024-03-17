<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Replenishment extends Model
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
        'replenishments'
    ];
}
