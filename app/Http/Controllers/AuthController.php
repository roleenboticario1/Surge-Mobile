<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Mail\VerificationEmail;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    public function register(Request $request)
    {
       $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'lastname' => 'required',
            'date_of_birth' => 'required',
            'mobile_number' => 'required|numeric|digits:11',
            'house_number' => 'required',
            'street' => 'required',
            'city' => 'required',
            'province' => 'required',
            'country' => 'required',
            'learned_about_serge_mobile' =>'required',
            'email' => 'required|email|max:191|unique:users,email',
            'password' => 'required|min:8',
            'repeat_password' => 'required|min:8|same:password',
       ]);

       if($validator->fails())
       {
          return response()->json([
               'validation_errors'=>$validator->messages()
          ], 422);
       }
       else
       {
       	  $users = User::create([
             'lastname'=> $request->lastname,
             'firstname'=> $request->firstname, 
             'date_of_birth'=> Carbon::parse($request->date_of_birth)->format('Y/m/d'),
             'mobile_number'=> $request->mobile_number,
             'house_number'=> $request->house_number,
             'street'=> $request->street,
             'city'=> $request->city,
             'province'=> $request->province, 
             'country'=> $request->country,
             'learned_about_serge_mobile'=> $request->learned_about_serge_mobile,
             'email'=> $request->email,
             'password'=>Hash::make($request->password),
             'email_verification_token' => Str::random(32)
       	  ]);
          
       	  $token = $users->createToken($users->email.'_Token')->plainTextToken;

          if($users)
          {  
             \Mail::to($users->email)->send(new VerificationEmail($users));
             return response()->json(['message' => 'Check your email to verify your account!'], 200);
          }else{
             return response()->json(['message' => 'User Not Register!'], 422);
          }
       }
    }


    public function login(Request $request)
    {
      $validator = Validator::make($request->all(), [
            'email' => 'required|max:191',
            'password' => 'required|min:8'
        ]);

        if($validator->fails())
        {
            return response()->json([
               'validation_errors'=>$validator->messages()
            ]);
        }
        else
        {   
              $user = User::where('email', $request->email)->first();

              if($user->email_verified == 1) 
              {
                  if (! $user || ! Hash::check($request->password, $user->password)) 
                  {
                        return response()->json(['message' => 'Invalid Credential!'], 401); 
                  }
                  else
                  {     
                        $user->last_login = Carbon::now();

                        if($user->role_as == 1)
                        {
                            $role = 'admin';
                            $token = $user->createToken($user->email.'_AdminToken', ['server:admin'])->plainTextToken; 
                        }
                        else
                        {   
                            $role = ''; 
                            $token = $user->createToken($user->email.'_Token', [''])->plainTextToken;
                        }
                        return response()->json(['token' => $token,'message' => 'Logged in Successfully!'], 200);      
                  }
              }

            
        }
    }


    public function logout()
    {
       auth()->user()->tokens()->delete();
       return response()->json(['message' => 'Logged out Successfully!'], 200);  
    }
}
