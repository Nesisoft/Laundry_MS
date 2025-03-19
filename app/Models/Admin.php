<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Admin extends Model
{
    protected $fillable = [
        'user_id', 'address_id', 'phone_number', 'first_name', 'last_name', 'sex'
    ];

    public function address(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
