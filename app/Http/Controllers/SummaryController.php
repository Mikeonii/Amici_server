<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\ItemTransaction;
use App\Models\Session;
use Carbon\Carbon;

class SummaryController extends Controller
{   
    public function print_monthly_sales($month,$year){

        // Ensure the month has 2 digits (e.g., 09 instead of 9)
        $month = str_pad($month, 2, '0', STR_PAD_LEFT);
        // Create the date with 'm-Y' format
        $date = Carbon::createFromFormat('m-Y', $month . '-' . $year)->format('F, Y');
        $sales = ItemTransaction::whereMonth('created_at',$month)->whereYear('created_at',$year)
        ->with('account')->with('item')->get();

        $walk_in_sales = Session::whereMonth('created_at',$month)->whereYear('created_at',$year)->get();
        
        return view('/forms/print_monthly_sales')->with('sales',$sales)->with('date',$date)->with('walk_in_sales',$walk_in_sales);
    }

    public function get_sales_summary(){
        $months = collect(["January","Febuary","March","April","May","June","July","August","September","October","November","December"]);
        $year = date('Y');
        $summary = collect([[ "Month","Sales"]]);   

        foreach($months as $index=>$month){
            $index+=1;

            $sales = ItemTransaction::whereMonth('created_at',$index)
            ->whereYear('created_at',$year)
            ->sum('amount');

            $sum = collect([
                $month,
                intval($sales)
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
