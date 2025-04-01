<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'sender_id',
        'receiver_id',
        'amount',
        'currency_id',
        'status',
        'sending_agent_id',
        'paying_agent_id',
        'transfer_date',
        'payment_date'
    ];

    protected $casts = [
        'transfer_date' => 'date',
        'payment_date' => 'date',
        'amount' => 'decimal:2'
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function sendingAgent()
    {
        return $this->belongsTo(User::class, 'sending_agent_id');
    }

    public function payingAgent()
    {
        return $this->belongsTo(User::class, 'paying_agent_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
