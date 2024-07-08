<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Attendance extends Model
{
    use HasFactory;
    public function account(){
        return $this->belongsTo(Account::class);
    }
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('F j, Y');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('F j, Y');
    }

    public function getLoggedInAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('F j, Y h:i:s A') : 'N/A';
    }
    
    public function getLoggedOutAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('F j, Y h:i:s A') : 'N/A';
    }
    
}
