<?php

namespace App\Models;

use App\Models\SendMoney;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SendMoneyActivit extends Model
{
    use HasFactory;

    public function send_money(){
        return $this->belongsTo(SendMoney::class);
    }
}