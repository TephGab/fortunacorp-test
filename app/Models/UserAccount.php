<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserAccount extends Model
{
    use HasFactory;

    protected $fillable = ['balance','depts','category', 'withdrawal', 'replenishments', 'investments', 'profits', 'referral_commissions', 'cash_in_hand', 'capital'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}