<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\ItemTransaction;
use Carbon\Carbon;

class SummaryController extends Controller
{
    public function get_attendance_summary(){

        $attendance_col = collect([["Day","Count"]]);
        $date = Carbon::now();
        $month = $date->format("m");
        $year = $date->format("Y");

        for($x = 0; $x<=31; $x++){
            $daily_count = Attendance::whereDay('created_at',$x)
            ->whereMonth('created_at',$month)
            ->whereYear('created_at',$year)
            ->count();
            $sum = collect([$x,$daily_count]);
            $attendance_col->push($sum);
        }
        return $attendance_col;
    }
}
