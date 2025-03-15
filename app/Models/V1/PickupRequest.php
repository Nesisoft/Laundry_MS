<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PickupRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id', 'customer_id', 'service_id', 'user_id',
        'location', 'latitude', 'longitude', 'date', 'time', 
        'amount', 'note', 'status'
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(PickupRequestPayment::class, 'request_id');
    }

    public function driverAssignments(): HasMany
    {
        return $this->hasMany(PickupRequestDriverAssignment::class, 'request_id');
    }
}
