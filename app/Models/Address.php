<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'street',
        'city',
        'state',
        'zip_code',
        'country',
        'latitude',
        'longitude'
    ];

    public function addressable()
    {
        return $this->morphTo();
    }

    public function businesses(): HasMany
    {
        return $this->hasMany(LocalConfig::class);
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}
