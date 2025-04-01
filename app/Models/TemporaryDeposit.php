<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryDeposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'depositor_id',
        'amount',
        'remaining_amount',
        'agent_id',
        'deposit_date'
    ];

    protected $casts = [
        'deposit_date' => 'date',
        'amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2'
    ];

    public function depositor()
    {
        return $this->belongsTo(User::class, 'depositor_id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function payments()
    {
        return $this->hasMany(DepositPayment::class);
    }
}
