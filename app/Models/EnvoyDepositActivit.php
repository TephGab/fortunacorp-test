<?php

namespace App\Models;

use App\Models\EnvoyDeposit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EnvoyDepositActivit extends Model
{
    use HasFactory;

    public function envoy_deposit(){
        return $this->belongsTo(EnvoyDeposit::class);
    }
}
