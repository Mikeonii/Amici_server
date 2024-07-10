<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class ItemTransaction extends Model
{
    use HasFactory;

    public function item(){
        return $this->belongsTo(Item::class);
    }

    public function account(){
        return $this->belongsTo(Account::class);
    }
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('F j, Y');
    }
}
