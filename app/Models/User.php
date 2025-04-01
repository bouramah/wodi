<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'phone_number',
        'password',
        'country_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getAuthIdentifierName()
    {
        return 'phone_number';
    }

    public function getAuthIdentifier()
    {
        return $this->phone_number;
    }

    /**
     * Get the country that owns the user.
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function sentTransfers()
    {
        return $this->hasMany(Transfer::class, 'sender_id');
    }

    public function receivedTransfers()
    {
        return $this->hasMany(Transfer::class, 'receiver_id');
    }

    public function sendingAgentTransfers()
    {
        return $this->hasMany(Transfer::class, 'sending_agent_id');
    }

    public function payingAgentTransfers()
    {
        return $this->hasMany(Transfer::class, 'paying_agent_id');
    }

    public function temporaryDeposits()
    {
        return $this->hasMany(TemporaryDeposit::class, 'agent_id');
    }

    public function depositorTemporaryDeposits()
    {
        return $this->hasMany(TemporaryDeposit::class, 'depositor_id');
    }
}
