<?php

namespace App\Models;

use App\Models\User;
use App\Models\UserSort;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContextSort extends Model
{
    use HasFactory;

    protected $fillable = [
        'context',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'context_sort_user_sort', 'context_sort_id', 'user_id')
            ->withTimestamps();
    }

    public function userSorts()
    {
        return $this->belongsToMany(UserSort::class, 'context_sort_user_sort', 'context_sort_id', 'user_sort_id')
            ->withTimestamps();
    }
}
