<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemSale;

class ItemSaleController extends Controller
{
    public function index(){
        return ItemSale::all();
    }

     public function store(Request $request){
        $new = $request->isMethod('put') ? ItemSale::findOrFail($request->id) : new ItemSale;
        $new->item_name = strtoupper($request->item_name);
        $new->unit_price = $request->unit_price;
        try{
            $new->save();
            return $new;
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function destroy($id){
        ItemSale::findOrFail($id->id)->delete();
        return true;
     }
}
