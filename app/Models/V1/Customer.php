<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id', 'user_id', 'address_id', 'email', 
        'phone_number', 'first_name', 'last_name', 
        'full_name', 'sex'
    ];

    public function address()
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customerDiscounts(): HasMany
    {
        return $this->hasMany(CustomerDiscount::class);
    }

    public function pickupRequests(): HasMany
    {
        return $this->hasMany(PickupRequest::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function deliveryRequests(): HasMany
    {
        return $this->hasMany(DeliveryRequest::class);
    }

    public function serviceRatings(): HasMany
    {
        return $this->hasMany(ServiceRating::class);
    }
}
