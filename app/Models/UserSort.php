<?php

namespace App\Models;

use App\Models\User;
use App\Models\ContextSort;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserSort extends Model
{
    use HasFactory;

    protected $fillable = [
        'selected_year',
        'selected_month',
        'id',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'context_sort_user_sort', 'user_sort_id', 'user_id')
            ->withTimestamps();
    }

    public function contextSorts()
    {
        return $this->belongsToMany(ContextSort::class, 'context_sort_user_sort', 'user_sort_id', 'context_sort_id')
            ->withTimestamps();
    }
}
