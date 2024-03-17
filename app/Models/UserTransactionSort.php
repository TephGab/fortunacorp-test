<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserTransactionSort extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'selected_year', 'selected_month'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
