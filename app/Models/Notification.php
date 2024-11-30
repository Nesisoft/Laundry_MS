<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'to', 'message'];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
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
