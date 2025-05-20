<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Hash;
class UserController extends Controller
{
    public function index(){
        return User::all();
    }
    public function store(Request $request){
        $new = $request->isMethod('put') ? User::findOrFail($request->id) : new User;
        $new->username = $request->username;
        $new->password = Hash::make($request->password);
        $new->name = $request->name;
        try{
            $new->save();
            return $new;
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }
}
