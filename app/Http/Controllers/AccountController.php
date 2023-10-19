<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
class AccountController extends Controller
{
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
                $rank = "Pegasus";
                break;
            case ($no_of_attendances >= 150):
                $rank = "Phoenix";
                break;
            case ($no_of_attendances >= 250):
                $rank = "Dragon";
                break;
            case ($no_of_attendances >= 360):
                $rank = "Cerberus";
                break;
            default:
                $rank = "Minotaur";
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
