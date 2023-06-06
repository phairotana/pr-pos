<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CategoryResource;

class SettingController extends Controller
{
    public function index()
    {
        return response([
            'policy' => [
                'title' => 'Privacy Policy',
                'url' => config('settings.policy')
            ],
            'about' => [
                'title' => 'About Us',
                'logo' => '',
                'description' => config('settings.about')
            ],
            'contact_us' => [
                'company_name' => config('settings.company_name'),
                'phone' => config('settings.contact_number'),
                'address' => config('settings.company_address')
            ]
        ],200);
    }
}
