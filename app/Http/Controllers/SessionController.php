<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\Session;
use Exception;
use Carbon\Carbon;
class SessionController extends Controller
{
    public function index() {
        // Get sessions and order by 'id' in descending order
        $sessions = Session::orderBy('id', 'DESC')->get();
        return $sessions;
    }
    
    public function store(Request $request){
        $new = $request->isMethod('put') ? Session::findOrFail($request->id) : new Session;
        $new->customer_name = $request->customer_name;
        $new->customer_gender = $request->customer_gender;
        $new->address = $request->address;
        $new->amount_paid = $request->amount_paid;
        $new->date_inserted = Carbon::now()->format("Y-m-d H:i:s");
        try{
            $new->save();
            return $new;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
}
