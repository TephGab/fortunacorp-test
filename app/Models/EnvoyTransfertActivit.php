<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnvoyTransfertActivit extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'status', 
        'amount', 
        'currency',
        'user_id',
        'confirmed_by_receiver',
        'receiver_confirmation_date',
        'sender_current_depts',
        'sender_new_depts',
        'receiver_current_depts',
        'receiver_new_depts',
        'current_sender_referral_commission',
        'new_sender_referral_commission',
        'current_receiver_referral_commission',
        'new_receiver_referral_commission'
    ];
}
