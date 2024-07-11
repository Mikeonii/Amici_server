<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\Measurement;
class AccountController extends Controller
{   
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
        ->limit(10) // Limit the results to the top 5
        ->get();
    return $topAttendees;

    // $topAttendees will contain the top 5 users with the most attendances
    
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
        $new = $request->isMethod('put') ? Account::findOrFail($request->id) : new Account;
        $new->name = strtoupper($request->input('name'));
        $new->birth_date = $request->input('birth_date');
        $new->gender = $request->input('gender');
        $new->card_no = $request->input('card_no');
        $new->address = strtoupper($request->input('address'));
        $new->total_gym_time =0;
        $new->total_attendance_rows = 0;
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
    
}
