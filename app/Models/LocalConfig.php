<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocalConfig extends Model
{
    protected $fillable = ['key', 'value'];
}
