<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemTransaction;
use App\Http\Controllers\CreditTransactionController;
class ItemTransactionController extends Controller
{
    public function show($account_id){
        return ItemTransaction::where('account_id',$account_id)->with('item')->get();

    }

    public function store(Request $request){
        foreach($request->input('request') as $req){
            $new = new ItemTransaction;
            $new->account_id = $req['account_id'];
            $new->item_id = $req['item_id'];
            $new->amount = $req['total_amount'];
            $new->quantity = $req['quantity'];
            try{
                // subtract credit
          
                $creditData = [
                    'account_id' => $req['account_id'],
                    'transaction_type' => 'Subtract',
                    'amount' => $req['total_amount'],
                ];
                CreditTransactionController::store(new Request($creditData));
                $new->save();
            }
            catch(Exception $e){
                return $e->getMessage();
            }
        };
        return $new; // move return outside of foreach loop
    }
    
}
