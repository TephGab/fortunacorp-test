<?php

namespace App\Models;

use App\Models\User;
use App\Models\Account;
use App\Models\CashinActivit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cashin extends Model
{
    use HasFactory;

    public function provider(){
        return $this->belongsTo(Provider::class);
    }

    public function account(){
        return $this->belongsTo(Account::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function cashin_activits(){
        return $this->hasMany(CashinActivit::class);
    }
    
}
