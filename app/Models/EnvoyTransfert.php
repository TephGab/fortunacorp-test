<?php

namespace App\Models;

use App\Models\User;
use App\Models\EnvoyTransfertActivit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EnvoyTransfert extends Model
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
        'receiver_new_depts'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function receiver() {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function envoy_transfert_activits(){
        return $this->hasMany(EnvoyTransfertActivit::class);
    }
}
