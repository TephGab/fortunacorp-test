<?php

namespace App\Models;

use App\Models\Cashin;
use App\Models\ProviderPayment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Provider extends Model
{
    use HasFactory;

    protected $fillable = ['name','phone', 'email', 'address', 'claim'];

    public function cashin(){
        return $this->hasMany(Cashin::class);
    }

    public function provider_payments(){
        return $this->hasMany(ProviderPayment::class);
    }
}
