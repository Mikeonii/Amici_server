<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Account extends Model
{
    use HasFactory;

    public function item_transactions(){
        return $this->hasMany(ItemTransaction::class);
    }
    public function attendances(){
        return $this->hasMany(Attendance::class);
    }
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('F j, Y');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('F j, Y');
    }

    public function getExpiryDateAttribute($value)
    {
        return Carbon::parse($value)->format('F j, Y');
    }

    
}
