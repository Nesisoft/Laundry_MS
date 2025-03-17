<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Business extends Model
{
    use HasFactory;

    protected $fillable = [
        'address_id', 'access_token', 'mode', 'name', 'phone', 'email', 'logo', 'banner', 'motto'
    ];

    public function address()
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }

    public function drivers(): HasMany
    {
        return $this->hasMany(Driver::class);
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function discounts(): HasMany
    {
        return $this->hasMany(Discount::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function pickupRequests(): HasMany
    {
        return $this->hasMany(PickupRequest::class);
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
