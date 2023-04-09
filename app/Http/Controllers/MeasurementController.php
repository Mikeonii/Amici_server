<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Measurement;

class MeasurementController extends Controller
{
    public function show($account_id){
        return Measurement::where('account_id',$account_id)->get();
    }

    public function store(Request $request){
        $new = $request->isMethod('put') ? Measurement::findOrFail($request->id) : new Measurement;
        $new->account_id = $request->input('account_id');
        $new->upper_arm = $request->input('upper_arm');
        $new->forearm = $request->input('forearm');
        $new->chest = $request->input('chest');
        $new->thigh = $request->input('thigh');
        $new->calf = $request->input('calf');
        $new->waist = $request->input('waist');
        $new->shoulder = $request->input('shoulder');
        try{
            $new->save();
            return $new;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
}
