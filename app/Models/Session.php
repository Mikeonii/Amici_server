<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Session extends Model
{
    use HasFactory;
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->setTimezone(config('app.timezone'))->format('F j, Y g:i A');
    }
}
