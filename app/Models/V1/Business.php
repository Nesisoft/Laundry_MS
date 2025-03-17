<?php

namespace App\Models\V1;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }
}
