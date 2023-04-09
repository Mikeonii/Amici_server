<?php

namespace App\Http\Controllers;

use App\Models\CreditTransaction;
use Illuminate\Http\Request;
use App\Http\Controllers\AccountController;
use Exception;
class CreditTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public static function store(Request $request)
    {
        $new = $request->isMethod('put') ? CreditTransaction::findOrFail($request->id) : new CreditTransaction;
        $new->account_id = $request->input('account_id');
        $new->transaction_type = $request->input('transaction_type');
        $new->amount = $request->input('amount');
        try{
            $new->save();
            // perform credit to account
            $credits = AccountController::insert_credit($new->transaction_type,$new->amount,$new->account_id);
            return CreditTransaction::findOrFail($new->id);
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CreditTransaction  $creditTransaction
     * @return \Illuminate\Http\Response
     */
    public function show($account_id)
    {
        return CreditTransaction::where('account_id',$account_id)->get();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CreditTransaction  $creditTransaction
     * @return \Illuminate\Http\Response
     */
    public function edit(CreditTransaction $creditTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CreditTransaction  $creditTransaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CreditTransaction $creditTransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CreditTransaction  $creditTransaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(CreditTransaction $creditTransaction)
    {
        //
    }
    
}
