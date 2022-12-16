<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller{
   // register new user
    public function register(Request $request)
    {
      // data verification
       $request->validate([
        'name' => 'required|unique:users',
        'email' => 'required|email|unique:users',
        'password' => 'required|confirmed'
       ]);
      //  insert data
       $user = new User();
       $user->name = $request->name;
       $user->email = $request->email;
       $user->password = Hash::make($request->password);
       if($request->image){
         $user->image_profile = $request->image;
       }
       $user->id_role = 2;
      //  save data
       $user->save();
      //  response 
       return response($user, Response::HTTP_CREATED);
    }
   //  method login
    public function login(Request $request)
    {
      // data verification
       $credentials = $request->validate([
            'email' => ['required','email'],
            'password' => 'required'
       ]);
      //  check if the data is correct
       if(Auth::attempt($credentials) ){
            $user = Auth::user();
            $token = $user->createToken('token')->plainTextToken;
            $cookie = cookie('cookie_token', $token, 60 * 24);
            // response with the token of the user
            return response(["token" => $token], Response::HTTP_OK)->withoutCookie($cookie);
       }else{
            return response(["message"=>"credetials not valids"],Response::HTTP_UNAUTHORIZED);
       }
    }
   //  method for user data
    public function userProfile()
    {
      // verification data of the user for the token 
       $user = Auth::user();
      //  responser data of the user
       return response()->json($user, Response::HTTP_OK);
    }

   //  method for closed assignment
    public function logout()
    {
       //  delete token of the user
      $user = Auth::user();
      $user->Tokens()->delete();

       // delete token of tha cookie
       $cookie = Cookie::forget('cookie_token');
      //  response 
       return response(['message'=>'Successfully logged out'],Response::HTTP_OK)->withCookie($cookie);
    }

}
