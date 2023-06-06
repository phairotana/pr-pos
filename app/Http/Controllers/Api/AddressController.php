<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    //
    public function index(Request $request)
    {
        if(session()->get('locale') == 'kh'){
            return Address::where('_code','Like',$request->code."__")
                ->orderBy('_name_en')
                ->pluck('_code','_name_kh');
        }else{
            return Address::where('_code','Like',$request->code."__")
                ->orderBy('_name_en')
                ->pluck('_code','_name_en');
        }
    }
}
