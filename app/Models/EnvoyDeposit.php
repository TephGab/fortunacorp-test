<?php

namespace App\Models;

use App\Models\User;
use App\Models\EnvoyDepositActivit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EnvoyDeposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'status', 
        'amount', 
        'currency',
        'user_id',
        'current_balance',
        'new_balance',
        'current_depts',
        'new_depts',
        'confirmed_by_receiver',
        'receiver_confirmation_date',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function admin() {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function envoy_deposit_activits(){
        return $this->hasMany(EnvoyDepositActivit::class);
    }
}
