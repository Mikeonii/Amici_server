<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemTransaction;
use App\Http\Controllers\CreditTransactionController;
use App\Models\Account;
use Carbon\Carbon;
class ItemTransactionController extends Controller
{   
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
        $new = new ItemTransaction;
        $new->account_id = $request->account_id;
        $new->item_id = $request->id;
        $new->amount = $request->total_amount;
        $new->quantity = $request->quantity;
        try{
            $new->save();
            // if this is monthly renewal for normal and student members,
            if($request->id == 2 || $request->id == 3){
                $acc = Account::findOrFail($request->account_id);
                $expiry_date = Carbon::parse($acc->expiry_date);
                $expiry_date = $expiry_date->addMonth($request->quantity)->format('Y-m-d');
                $acc->expiry_date = $expiry_date;
                $acc->save();
                return $acc->load('item_transactions');
            }
            // for yearly membership
            else if($request->id == 1){
                $acc = Account::findOrFail($request->account_id);
                $yearly_expiry_date = Carbon::parse($acc->yearly_expiry_date);
                $yearly_expiry_date = $yearly_expiry_date->addYear($request->quantity)->format('Y-m-d');
                $acc->yearly_expiry_date = $yearly_expiry_date;
                $acc->save();
                return $acc->load('item_transactions');
            }
            $creditData = [
                'account_id' => $request['account_id'],
                'transaction_type' => 'Subtract',
                'amount' => $request['total_amount'],
            ];
            // insert new credit transaction
            CreditTransactionController::store(new Request($creditData));
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
    
}
