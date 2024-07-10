<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\ItemTransaction;
use Carbon\Carbon;

class SummaryController extends Controller
{   
    public function get_sales_summary(){
        $months = collect(["January","Febuary","March","April","May","June","July","August","September","October","November","December"]);
        $year = date('Y');
        $summary = collect([[ "Year","Sales"]]);   

        foreach($months as $index=>$month){
            $index+=1;

            $sales = ItemTransaction::whereMonth('created_at',$index)
            ->whereYear('created_at',$year)
            ->sum('amount');

            $sum = collect([
                $month,
                $sales
            ]);
            $summary->push($sum);
        }
        return $summary;
    }


    public function get_attendance_summary(){
        $attendance_col = collect([["Day","Total","Male","Female"]]);
        $date = Carbon::now();
        $month = $date->format("m");
        $year = $date->format("Y");

        for($x = 0; $x<=31; $x++){
            // total count
            $daily_count = Attendance::whereDay('created_at',$x)
            ->whereMonth('created_at',$month)
            ->whereYear('created_at',$year)
            ->count();
  
            // total male count
            $male_count = Attendance::whereDay('created_at', $x)
            ->whereHas('account', function ($query) {
                $query->where('gender', 'Male');
            })
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->count();

            // total female count
            $female_count = Attendance::whereDay('created_at', $x)
            ->whereHas('account', function ($query) {
                $query->where('gender', 'Female');
            })
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->count();

            $sum = collect([$x,$daily_count,$male_count,$female_count]);
            $attendance_col->push($sum);
            
        }
        return $attendance_col;
    }
}
