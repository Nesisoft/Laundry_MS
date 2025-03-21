<?php

namespace App\Models;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeliveryRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'customer_id', 'user_id',
        'location', 'latitude', 'longitude', 'date', 'time',
        'amount', 'note', 'status'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(DeliveryRequestPayment::class, 'request_id');
    }

    public function driverAssignments(): HasMany
    {
        return $this->hasMany(DeliveryRequestDriverAssignment::class, 'request_id');
    }
}
