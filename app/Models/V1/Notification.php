<?php

namespace App\Models\V1;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'to', 'message'];

    // public function business(): BelongsTo
    // {
    //     return $this->belongsTo(Business::class);
    // }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function customers(): BelongsTo
    {
        return $this->BelongsTo(Customer::class);
    }

    public function templates(): BelongsTo
    {
        return $this->BelongsTo(NotificationTemplate::class);
    }

}
