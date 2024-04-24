<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //
     private $secretKey="eBHPqbcgG/KqBh/uQ7IAZ+zNC77OE8d3c4ikYZW60=eBHPqbcgG/KqBh/uQ7IAZ+zNC77OE8d3c4ikYZW60=";
     public function register(Request $request)
     {  
        $fields = $request->all();

        $errors = Validator::make($fields,[
            'name'=>'required|string',
            'email'=>'required|string|email|unique:users,email',
            'password'=>'required',
            
        ]);
        if($errors->fails()){
            return response($errors->errors()->all(),422);
        }

        $user=User::create([
            'name'=>$fields['name'],
            'email'=>$fields['email'],
            'password'=>bcrypt($fields['password']),
        ]);

        return response([
            'user'=>$user,
            'message'=>'Your account was created!!'
        ]);

     }//end method

     public function login(Request $request)
     {
        $fields = $request->all();

        $errors = Validator::make($fields,[
            'email'=>'required|string|email',
            'password'=>'required',
        ]);

        if($errors->fails()){
            return response($errors->errors()->all(),422);
        }
        $user=User::where('email',$fields['email'])->first();
        if(!$user || !Hash::check($fields['password'],$user->password)){
            return response(['message'=>'email or password invalid'], 401);
        }
        $token=$user->createToken($this->secretKey)->plainTextToken;

        return response([
            'user'=>$user,
            'token'=>$token,
        ],201);


     }//end method


}
