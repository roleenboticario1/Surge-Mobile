<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VerifyController extends Controller
{
    public function VerifyEmail($token = null)
    {
    	if($token == null) {
    		return response()->json(['message' => 'message', 'Invalid Login attempt'], 422);
    		return redirect()->route('login');
    	}

       $user = User::where('email_verification_token',$token)->first();

       if($user == null ){
       	  return response()->json(['message' => 'message', 'Invalid Login attempt'], 422);
          return redirect()->route('login');
       }
       $user->update([
         'email_verified' => 1,
         'email_verified_at' => Carbon::now(),
         'email_verification_token' => ''
       ]);

      return response()->json(['message' => 'Your account is activated, you can log in now']);
    }
}
