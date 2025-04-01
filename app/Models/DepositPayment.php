<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'temporary_deposit_id',
        'amount_paid',
        'payment_date'
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount_paid' => 'decimal:2'
    ];

    public function temporaryDeposit()
    {
        return $this->belongsTo(TemporaryDeposit::class);
    }
}
