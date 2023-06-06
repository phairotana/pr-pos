<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string',
            'last_name' => 'required|string',
            'phone'     => 'required|string|unique:users,phone',
            'address'   => 'required|string',
            'email'     => 'required|string|email|max:255|unique:users,email',
            'password'  => 'required'
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        $user = User::updateOrCreate([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'password' => Hash::make($request->password)
        ]);
        Customer::updateOrCreate([
            'customer_phone' => $request->phone
        ],[
            'customer_name'      => $request->name,
            'customer_last_name' => $request->last_name,
            'customer_phone'     => $request->phone,
            'customer_address'   => $request->naaddressme,
            "customer_email"     => $request->email
        ]);
        $token = $user->createToken('Laravel Password Grant Client')->accessToken;
        $response = ['token' => $token];
        return response($response, 200);
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required',
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                $response = ['token' => $token];
                return response($response, 200);
            } else {
                $response = ["message" => "Password mismatch"];
                return response($response, 422);
            }
        } else {
            $response = ["message" =>'User does not exist'];
            return response($response, 422);
        }
    }
    public function update(Request $request) 
    {
        $user = \Auth::user();
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string',
            'last_name' => 'required|string',
            'phone'     => 'required|string|unique:users,phone,'.$user->id,
            'address'   => 'required|string'
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        $user->customer()->update([
            'customer_name'      => $request->name,
            'customer_last_name' => $request->last_name,
            'customer_phone'     => $request->phone,
            'customer_address'   => $request->naaddressme
        ]);
        $user->update($request->all());
        $response = ["message" => "The user updated succesfully."];
        return response($response, 200);
    }
    public function uploadProfile(Request $request) 
    {
        $user = \Auth::user();
        $validator = Validator::make($request->all(), [
            'profile'  => 'required',
        ]);
        if ($validator->fails()){
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        $user->update([
            'profile' => $request->profile
        ]);
        $response = ["message" => "The user updated succesfully."];
        return response($response, 200);
    }
    public function changePassword(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password']
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
   
        $user = \Auth::user();
        $user->update(['password'=> Hash::make($request->new_password)]);
        $response = ["message" => "The user password has been updated succesfully."];
        return response($response, 200);
    }
    public function user() 
    {
        $response = ['data' => auth('api')->user()];
        $request['data']['profile'] = auth('api')->user()->LargeProfile;
        return response($response, 200);
    }
    public function logout(Request $request) {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }
}
