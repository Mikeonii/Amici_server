<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\Measurement;
use App\Models\Attendance;
use Carbon\Carbon;
class AccountController extends Controller
{   
    public function adjustDate($date, $operation, $intervalType, $count) {
        switch ($intervalType) {
            case 'Days':
                return $operation == 'add' ? $date->addDays($count) : $date->subDays($count);
            case 'Weeks':
                return $operation == 'add' ? $date->addWeeks($count) : $date->subWeeks($count);
            case 'Months':
                return $operation == 'add' ? $date->addMonths($count) : $date->subMonths($count);
            case 'Years':
                return $operation == 'add' ? $date->addYears($count) : $date->subYears($count);
            default:
                throw new InvalidArgumentException("Invalid time interval: $intervalType");
        }
    }
    public function modify_expiry_dates(Request $request){
       $acc = Account::findOrFail($request->id);
       $monthly_expiry = Carbon::parse($acc->expiry_date);
       $yearly_expiry = Carbon::parse($acc->yearly_expiry_date);
    
       if ($request->column == 'Monthly Expiration') {
        $acc->expiry_date = $this->adjustDate($monthly_expiry, $request->operation, $request->time_intervals, $request->count);
        } else if ($request->column == 'Yearly Expiration') {
            $acc->yearly_expiry_date = $this->adjustDate($yearly_expiry, $request->operation, $request->time_intervals, $request->count);
        }
        
       try{
            $acc->save();
            return $acc;
       }
       catch(Exception $e){
            return $e->getMessage();
       }
    }
    public function upload_body_improvement_picture(Request $request){
    //get the value of id
    $measurement = Measurement::where($request->input('id'));
    $measurement->photo_url = $request->input('body_improvement_picture_url');
    try{
        $measurement->save();
        return $measurement;
    }
    catch(Exception $e){
        return $e->getMessage();
    }
}
    public function upload_profile_picture(Request $request){
          //get the value of id
          $account = Account::find($request->input('id'));
          $account->profile_picture_url = $request->input('profile_picture_url');
          try{
              $account->save();
              return $account;
          }
          catch(Exception $e){
              return $e->getMessage();
          }
    }
    public function print_waiver_form($account_id){
        $acc = Account::where('id',$account_id)->first();
        return view('forms/print_waiver_form')->with('acc',$acc);
    }

    public function get_top_gymmers(){
        $topAttendees = Account::withCount('attendances as attendance_count') // Count the attendance records for each user
        ->orderBy('attendance_count', 'desc') // Order by the correct alias, 'attendance_count', in descending order
        ->get();
    return $topAttendees;

    // $topAttendees will contain the top 5 users with the most attendances
    
    }
    public function get_top_gymmer_of_current_month() {
        $currentMonth = Carbon::now()->month; // Get current month
        // $currentMonth = 10;
        $currentYear = Carbon::now()->year; // Get current year
        // Fetch the top gymmer of the current month
        $topGymmer = Account::whereHas('attendances', function ($query) use ($currentMonth, $currentYear) {
            // Filter attendances for the current month and year
            $query->whereMonth('created_at', $currentMonth)
                  ->whereYear('created_at', $currentYear);
        })
        ->withCount(['attendances' => function ($query) use ($currentMonth, $currentYear) {
            // Count attendances for the current month and year
            $query->whereMonth('created_at', $currentMonth)
                  ->whereYear('created_at', $currentYear);
        }])
        ->orderBy('attendances_count', 'desc') // Order by attendance count
        ->orderBy('total_gym_time','desc')
        ->limit(10)
        ->get(); // Get the top gymmer
    
        return $topGymmer;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Account::all();
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
    public function store(Request $request)
    {

        $dateToday = Carbon::now()->format("Y-m-d");
        $new = $request->isMethod('put') ? Account::findOrFail($request->id) : new Account;
        $new->name = strtoupper($request->input('name'));
        $new->birth_date = $request->input('birth_date');
        $new->gender = $request->input('gender');
        $new->card_no = $request->input('card_no');
        $new->address = strtoupper($request->input('address'));
        if($request->isMethod('put')) $new->expiry_date = $dateToday;
        $new->phone_number = $request->phone_number;
        $new->yearly_expiry_date = Carbon::now()->addYears(1);
        $new->total_gym_time =0;
        $new->total_attendance_rows = 0;
        $new->phone_number = $request->input('phone_number');
        if($request->isMethod('post')){
            $new->total_gym_time = 0;
            $new->total_attendance_rows = 0;
        }

        try{
            $new->save();
            $new = Account::find($new->id);
            return $new;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // delete all attendances
        $atts = Attendance::where('account_id',$id)->delete();
        return Account::findOrFail($id)->delete();
    }

    public static function insert_credit($transaction_type, $amount, $account_id) {
        $operator = "+";
        if ($transaction_type == 'Subtract') {
            $operator = '-';
        }
        $acc = Account::where('id', $account_id)->first();
        $acc->credits = ($operator === '+') ? ($acc->credits + $amount) : ($acc->credits - $amount);
        try {
            $acc->save();
            return $acc->credits;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public static function update_rank($account_id, $no_of_attendances){
        $rank = "";
        switch (true) {
            case ($no_of_attendances >= 60):
                $rank = "Lifter";
                break;
            case ($no_of_attendances >= 90):
                $rank = "Veteran";
                break;
            case ($no_of_attendances >= 150):
                $rank = "Master";
                break;
            case ($no_of_attendances >= 250):
                $rank = "Legendary";
                break;
            case ($no_of_attendances >= 365):
                $rank = "Beast";
                break;
            default:
                $rank = "Novice";
                break;
        }
    
        $acc = Account::findOrFail($account_id);
        $acc->rank = $rank;
        try{
            $acc->save();
        }
        catch(Exception $e){
            return $acc->getMessage();
        }
    }

    public function addYear() {
        $accounts = Account::get();
        foreach ($accounts as $account) {
            try {
                // Ensure yearly_expiry_date is properly parsed to a Carbon instance
                $yearly = Carbon::parse($account->yearly_expiry_date);
                $account->yearly_expiry_date = $yearly->addYear();
                $account->save();
            } catch (Exception $e) {
                return $e->getMessage();
            }
        }
        return "added 1 year";
    }
    
}

