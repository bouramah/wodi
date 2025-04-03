<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function sourceTransfers()
    {
        return $this->hasMany(Transfer::class, 'source_country_id');
    }

    public function destinationTransfers()
    {
        return $this->hasMany(Transfer::class, 'destination_country_id');
    }
}
