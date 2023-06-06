<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Customer;
use App\Libraries\OTPLib;
use App\Models\VerifyOTP;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class OTPController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'phone' => 'required|string'
        ]);

        OTPLib::sendAnySms($request->phone);

        return response([
            'success' => true,
            'message' => 'The OTP has been send.'
        ]);
    }
    public function verify(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'code' => 'required|string|digits:4'
        ]);

        $isExists = VerifyOTP::where('phone', $request->phone)
                                ->where('code', $request->code)
                                ->where('expire','>=', Carbon::now())
                                ->exists();
        if($isExists){
            $user = User::firstOrCreate([
                'phone' => $request->phone,
            ],[
                'name' => $request->phone,
                'password'  => \Hash::make($request->phone)
            ]);
            Customer::firstOrCreate([
                'customer_phone' => $request->phone
            ]);
            $token = $user->createToken('Laravel Password Grant Client')->accessToken;
            $response = ['token' => $token];
            return response($response, 200);
        }else{
            return response([
                'success' => false,
                'message' => 'Incorrect verification code.'
            ]);
        }
    }
}
