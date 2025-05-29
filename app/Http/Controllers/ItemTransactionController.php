<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemTransaction;
use App\Http\Controllers\CreditTransactionController;
use App\Models\Account;
use App\Models\Item;
use Carbon\Carbon;
use Auth;
class ItemTransactionController extends Controller
{   

    public function index(){
        return ItemTransaction::with('account','item')->orderBy('id','DESC')->get();
    }
    public function get_all_item_transactions(){
        $item_transactions = ItemTransaction::with('item','account')->orderBy('created_at','DESC')->get();
        return $item_transactions;
    }

    public function get_daily_sales($day,$month,$year){
        $sales = ItemTransaction::with('item','account')
        ->whereDate('created_at',$day)
        ->whereMonth('created_at',$month)
        ->whereYear('created_at',$year)
        ->orderBy('created_at','DESC')
        ->get();
        return $sales;
    }
    public function get_yearly_sales(){
        // get the total sales in service,items,over all net,and expense for the last 12 months.
        $months = collect(["January","Febuary","March","April","May","June","July","August","September","October","November","December"]);
        $year = date('Y');
        $summary = collect([[ "Year",
        "Attendances",
        ]]);   
        foreach($months as $index=>$month){
            $index+=1;
           
            $sales = ItemTransaction::whereMonth('created_at',$index)
            ->whereYear('created_at',$year)
            ->sum('amount');

            $sum = collect([$month,intval($sales)]);
            $summary->push($sum);
        }
        return $summary;
    }
    public function show($account_id){
        return ItemTransaction::where('account_id',$account_id)->with('item')->get();
    }
    public function store(Request $request){
        $userName = Auth()->user()->name;
        $new = new ItemTransaction;
        $new->account_id = $request->account_id;
        $new->item_id = $request->id;
        $new->amount = $request->total_amount;
        $new->quantity = $request->quantity;
        $new->posted_by = $userName;
        $new->payment_method =$request->payment_method;

        $monthlyId = Item::where('item_type','Monthly Subscription')->value('id');
        $yearlyId = Item::where('item_type','Membership')->value('id');
        $promoMonthlyId = Item::where('item_type','Discounted Monthly Subscription')->value('id');
        $promoYearlyId = Item::where('item_type','Discounted Membership')->value('id');

        $acc = Account::findOrFail($request->account_id);
      
        try{
            $new->save();
            // if this is monthly renewal
            if($request->id == $monthlyId || $request->id == $promoMonthlyId){
             
                
                if($request->method == 'Renew') $expiry_date = Carbon::now();
                if($request->method == 'Continue') $expiry_date = Carbon::parse($acc->expiry_date); // this is based on the last monthly expiry date
                
                $expiry_date = $expiry_date->addMonth($request->quantity)->format('Y-m-d');
                $acc->expiry_date = $expiry_date;
                $acc->save();
                return $acc->load('item_transactions');
            }
            // for yearly membership
            else if($request->id == $yearlyId || $request->id == $promoYearlyId){
              

                if($request->method == 'Renew') $yearly_expiry_date = Carbon::now(); //if renew then start to this date
                if($request->method == 'Continue') $yearly_expiry_date = Carbon::parse($acc->yearly_expiry_date); //if continue, start from the last date

                $yearly_expiry_date = $yearly_expiry_date->addYear($request->quantity)->format('Y-m-d');
                $acc->yearly_expiry_date = $yearly_expiry_date;
                $acc->save();
                return $acc->load('item_transactions');
            }
            else{
                $acc->save();
                return $acc->load('item_transactions');
            }
          
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
   

    
    }

    public function add_expiry_date($account_id,$quantity){
        
        try{
            $acc->save();
            return $acc;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function destroy($id){
        ItemTransaction::findOrFail($id)->delete();
        return true;
    }
    
    
}
