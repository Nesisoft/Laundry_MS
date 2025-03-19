<?php

namespace App\Models;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id', 'user_id', 'vehicle_id', 'address_id', 'phone_number', 'first_name', 'last_name', 'sex'
    ];

    public function address()
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function pickupRequestAssignments(): HasMany
    {
        return $this->hasMany(PickupRequestDriverAssignment::class);
    }

    public function deliveryRequestAssignments(): HasMany
    {
        return $this->hasMany(DeliveryRequestDriverAssignment::class);
    }
}
