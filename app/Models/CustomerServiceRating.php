<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerServiceRating extends Model
{
    protected $fillalble = ['added_by', 'customer_id', 'ratings', 'comment'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
