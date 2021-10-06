<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    
    public function index(){
        return User::all();
    }
    public function destroy($id){
        $des = User::destroy($id);
        return $des;
    }
    public function store(Request $request){
        $fields = $request->validate([
            'name'=>'required|string',
            'password'=>'required|string',
            'phone_number'=>'required',
            'username'=>'required'
        ]);
        $new_pass = Hash::make($request->password);
        $request->password = $new_pass;

        $User = User::create([

            'name'=>$fields['name'],
            'password'=>$new_pass,
            'phone_number'=>$fields['phone_number'],
            'username'=>$fields['username']
        ]);
        return $User;
    }
    public function signin(Request $request){
        $fields = $request->validate([
            'username'=>'required',
            'password'=>'required'
        ]);

        // check if exist
        $username = User::where('username', $fields['username'])->first();    
        if(!$username || !Hash::check($fields['password'],$username->password)){
            return Response(['message'=>'Bad Credentials'],401);
        }
        else{
            $token = $username->createToken('myapptoken')->plainTextToken;
            // $token = $request->user()->createToken($request->token_name);
            return $token;
        }
    }
    public function attempt(){
        $user = auth()->user();
        return $user;
    }
    public function signout(Request $request){
        $request->user()->currentAccessToken()->delete();
    }
}
