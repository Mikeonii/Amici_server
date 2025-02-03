<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Account;
use App\Models\ItemTransaction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\AccountController;
class AttendanceController extends Controller
{
    
    public function get_total_gym_time($time_in,$time_out){
        $time_in = Carbon::parse($time_in); // Convert time_in to Carbon instance
        $time_out = Carbon::parse($time_out); // Convert time_out to Carbon instance
    
        $difference = $time_out->diffInMinutes($time_in); // Calculate difference in minutes
    
        // $minutes = $difference % 60; //calculate minutes
        return $difference;
    }

    public function get_yearly_attendances(){
        // get the total sales in service,items,over all net,and expense for the last 12 months.
        $months = collect(["January","Febuary","March","April","May","June","July","August","September","October","November","December"]);
        $year = date('Y');
        $summary = collect([[ "Year",
        "Attendances",
        ]]);   
        foreach($months as $index=>$month){
            $index+=1;
           
            $attendances = Attendance::whereMonth('created_at',$index)
            ->whereYear('created_at',$year)
            ->count();

            $sum = collect([$month,intval($attendances)]);
            $summary->push($sum);
        }
        return $summary;
    }

    public function is_expired($account_id){
        $account = Account::findOrFail($account_id);
        // Get the current date
        $today = Carbon::now();
        // Convert expiry_date to a Carbon object for comparison
        $expiry_date = Carbon::parse($account->expiry_date);
        // Check if the account's expiry_date is today or less than today
        if ($expiry_date->lessThanOrEqualTo($today)) {
            return true; // Account is expired
        } else {
            return false; // Account is not expired
        }
    }
    
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $attendances = Attendance::with('account')->orderBy('created_at', 'DESC')->get();

        return $attendances;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($card_no)
    {   
     
        $timeToday = Carbon::now();
        $dateToday = Carbon::now()->format("Y-m-d");
       
        $account_id = $this->get_account_id($card_no);
        if(!$account_id) return "Account not found";
        //check first if account is expired or not
        if($this->is_expired($account_id) == true) return "Account Expired";
        $att = Attendance::where('account_id', $account_id)
            ->whereDate('created_at', $dateToday)
            ->with('account')
            ->first();
        
        // if no attendance
        if(!$att){
            $att = $this->create_attendance($timeToday,$account_id,$card_no);
        }
        // if exists and there's no log out,
        else if($att->logged_out == "N/A"){
            $att = $this->insert_logged_out($att,$timeToday);
            return collect([$att,"Thank you for coming in"]);
        }
         // Check if attendance already exists in rows.
        else if($att->logged_in && $att->logged_out){
            return "Account already logged in";
        }

      

        return $att;
    }

    private static function get_total_hours($time_in, $time_out){
        $time_in = Carbon::parse($time_in); // Convert time_in to Carbon instance
        $time_out = Carbon::parse($time_out); // Convert time_out to Carbon instance
    
        $difference = $time_out->diffInMinutes($time_in); // Calculate difference in minutes
    
        $hours = floor($difference / 60); // Calculate total hours
        $minutes = $difference % 60; // Calculate remaining minutes
    
        // Format the total hours and minutes as a string
        $formatted_hours = ($hours == 1) ? $hours . ' hour' : $hours . ' hours';
        $formatted_minutes = ($minutes == 1) ? $minutes . ' minute' : $minutes . ' minutes';
    
        // Build the final formatted string
        $formatted_string = $formatted_hours . ' and ' . $formatted_minutes;
    
        return $formatted_string; // Return the formatted string
    }


    public function get_total_attendance($account_id){
        $rows = Attendance::where('account_id',$account_id)->count();
        return $rows;

    }    

    private  function create_attendance($timeToday, $account_id, $card_no){
        $new = new Attendance;
        $new->account_id = $account_id;
        $new->logged_in = $timeToday;
        $new->card_no = $card_no;
        try {
            $new->save();
            $new = Attendance::where('id',$new->id)->with('account')->first();
            return $new;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    private function update_total_hours($account_id,$total_minutes){
        $acc = Account::findOrFail($account_id);
        $acc->total_gym_time+=$total_minutes;
        $acc->total_attendance_rows+=1;
        try{
            $acc->save();
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }

    private  function insert_logged_out($att,$timeToday){
        $att->logged_out = $timeToday;
        $att->total_hours = $this->get_total_hours($att->logged_in,$att->logged_out);
        // insert total gym hours
        $total_minutes = $this->get_total_gym_time($att->logged_in,$att->logged_out);
        // update account total hour
        $this->update_total_hours($att->account_id,$total_minutes);

        $rows = $this->get_total_attendance($att->account_id);
        AccountController::update_rank($att->account_id,$rows);
        try {
            $att->save();
            return $att;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function get_account_id($card_no){
        $account_id = Account::select('id')->where('card_no',$card_no)->first();
        if(!$account_id) return false;
        return $account_id->id;
    }
    
    

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function edit(Attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attendance $attendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attendance $attendance)
    {
        //
    }
}
