<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegistryManagementController extends Controller
{   

	public function index()
    {
        $users = User::all();
        
        if(count($users) > 0)
        {
           return response()->json([ $users ], 200);
        }else{
           return response()->json([ 'message' => "No Records Found!" ], 422);
        }
    }

    
    public function store(Request $request)
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
          return response()->json(['validation_errors'=>$validator->messages()], 422);
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
             'password'=>Hash::make($request->password)
          ]);
          
          $token = $users->createToken($users->email.'_Token')->plainTextToken;

          if($users)
          {
          	 return response()->json(['message' => 'Data Saved Successfully!'], 200);
          }else{
          	 return response()->json(['message' => 'Data Not Saved!'], 422);
          }
       }
    }


    public function show($id)
    {
        $users = User::find($id);
        
        if($users)
        {
           return response()->json([ $users ], 200);
        }else{
           return response()->json([ 'message' => "No Records Found!" ], 422);
        }
    }


    public function update(Request $request, $id)
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
          return response()->json(['validation_errors'=>$validator->messages()], 422);
       }
       else
       {
          $users = User::where('id', $id)
                     ->update([
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
                         'password'=>Hash::make($request->password)
                     ]);

          if($users)
          {
	           return response()->json(['message' => 'Data Updated Successfully!'], 200);

          }else{
               return response()->json(['message' => 'No Records Found!'], 422);
          }
       }
    }


    public function destroy($id)
    {
        $users = User::where('id', $id)->first();
 
        if($users != null)
        {     
        	$users->delete();
        	return response()->json([ 'message' => 'Data Deleted Successfully!' ], 200);
        }else{
              
            return response()->json(['message' => 'No Records Found!'], 422);
        }
    }

}
