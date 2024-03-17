<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'device_type',
        'session_id',
        'device_info',
        'login_time',
        'browser_name',
        'browser_version',
        'engine',
        'os',
        'os_version'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}