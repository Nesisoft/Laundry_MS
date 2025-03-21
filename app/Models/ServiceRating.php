<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceRating extends Model
{
    use HasFactory;

    protected $fillalble = ['added_by', 'customer_id', 'ratings', 'comment'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
