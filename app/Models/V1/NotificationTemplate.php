<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NotificationTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'category', 'medium', 'title', 'message'
    ];

    public function notifications() : HasMany {
        return $this->hasMany(Notification::class);
    }
}
