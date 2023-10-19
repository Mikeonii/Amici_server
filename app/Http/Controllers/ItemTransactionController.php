<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemTransaction;
use App\Http\Controllers\CreditTransactionController;
use App\Models\Account;
use Carbon\Carbon;
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
                 // check if item id is == 1(monthly access) or == 2(membership access);
                if($req['item_id'] == 1){
                    $this->add_gym_access_expiration_date($req['account_id'],$req['quantity']);
                }
                if($req['item_id'] == 2){
                    $this->add_membership_expiration_date($req['account_id'],$req['quantity']);
                }
                $new->save();
            }
            catch(Exception $e){
                return $e->getMessage();
            }
        };
        return $new; // move return outside of foreach loop
    }

    public function add_gym_access_expiration_date($account_id,$quantity){
        $acc = Account::findOrFail($account_id);
        $acc->gym_access_expiration_date = Carbon::now()->addMonth($quantity)->format('Y-m-d');
        try{
            $acc->save();
            return $acc;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function add_membership_expiration_date($account_id,$quantity){
        $acc = Account::findOrFail($account_id);
        $acc->membership_expiration_date = Carbon::now()->addYear($quantity)->format('Y-m-d');
        try{
            $acc->save();
            return $acc;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
    
}
