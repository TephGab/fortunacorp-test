<?php

namespace App\Models;

use App\Models\Client;
use App\Models\Account;
use App\Models\TransactionActivit;
use App\Models\TransactionComment;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use App\Models\ReplenishmentRequirement;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['sender_amount',
                            'receiver_amount',
                            'qr_code', 'rate', 
                            'fortuna_fee', 
                            'p_to_p_fee', 
                            'status', 
                            'type', 
                            'operation', 
                            'approved_by', 
                            'sender', 
                            'receiver', 
                            'approved_date',
                            'completed_by', 
                            'completed_date',
                            'account_id', 
                            'client_id', 
                            'user_id', 
                            'operator_id',
                            'viewed'];

                            
    public function client(){
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function sender(){
        return $this->belongsTo(Client::class, 'sender');
    }
    
    public function receiver(){
        return $this->belongsTo(Client::class, 'receiver');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function operator() {
        return $this->belongsTo(User::class, 'operator_id');
    }

    public function account() {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function transaction_comments(){
        return $this->hasMany(TransactionComment::class);
    }

    public function transaction_activits(){
        return $this->hasMany(TransactionActivit::class);
    }

    public function replenishment_requirements(){
        return $this->hasMany(ReplenishmentRequirement::class);
    }

     /**
     * Get the channels that model events should broadcast on.
     *
     * @param  string  $event
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn($event)
    {
        return [$this, $this->user];
    }
}
