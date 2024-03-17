<?php

namespace App\Models;

use App\Models\User;
use App\Models\CashoutActivit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cashout extends Model
{
    use HasFactory;

    protected $fillable = ['amount',
     'status', 
     'type', 
     'currency',
     'user_bank_account_id', 
     'system_bank_account_id', 
     'user_account_id',
     'user_in_person',
     'completed_by_admin',
     'confirmed_by_user',
     'confirmed_by_envoy',
     'admin_completion_date',
     'receiver_confirmation_date',
     'envoy_confirmation_date',
     'envoy_id', 'user_id', 
     'admin_id',
     'user_role',
     'use_envoy_debt'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function cashout_activits(){
        return $this->hasMany(CashoutActivit::class);
    }

    public function envoy() {
        return $this->belongsTo(User::class, 'envoy_id');
    }

    public function admin() {
        return $this->belongsTo(User::class, 'admin_id');
    }
}