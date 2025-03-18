<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'street', 'city', 'state', 'zip_code', 'country', 
        'latitude', 'longitude'
    ];

    public function addressable()
    {
        return $this->morphTo();
    }

    public function businesses(): HasMany
    {
        return $this->hasMany(Business::class);
    }

    public function drivers(): HasMany
    {
        return $this->hasMany(Driver::class);
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }
}
