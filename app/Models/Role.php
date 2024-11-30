<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $fillable = ['name'];
    
    public function roles(): HasMany
    {
        return $this->hasMany(Role::class);
    }
}
