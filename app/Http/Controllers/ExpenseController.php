<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use Auth;
use Exception;
class ExpenseController extends Controller
{
    public function index(){
        return Expense::all();
    }

    public function store(Request $request){
        $userName = Auth()->user()->name;
        $new = $request->isMethod('put') ? Expense::findOrFail($request->id) : new Expense;
        $new->particulars = $request->particulars;
        $new->amount = $request->amount;
        $new->posted_by = $userName;
        $new->category = $request->category;
        try{
            $new->save();
            return $new;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function destroy($id){
        Expense::findOrFail($id->id)->delete();
        return true;
    }
    
}
