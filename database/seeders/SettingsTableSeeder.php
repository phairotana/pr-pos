<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * The settings to add.
     * php artisan db:seed --class=SettingsTableSeeder
     */
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::firstOrCreate([
            'key'         => 'company_name'
        ],[
            'name'        => 'Company Name',
            'description' => '',
            'value'       => 'Your company name',
            'field'       => '{"name":"value","label":"Value","type":"text"}',
            'active'      => 1
        ]);
        Setting::firstOrCreate([
            'key'         => 'contact_name'
        ],[
            'name'        => 'Contact Name',
            'description' => '',
            'value'       => 'Contact name.',
            'field'       => '{"name":"value","label":"Value","type":"text"}',
            'active'      => 1
        ]);
        Setting::firstOrCreate([
            'key'         => 'contact_number'
        ],[
            'name'        => 'Contact Number',
            'description' => '',
            'value'       => 'Contact number.',
            'field'       => '{"name":"value","label":"Value","type":"text"}',
            'active'      => 1
        ]);
        Setting::firstOrCreate([
            'key'         => 'company_address'
        ],[
            'name'        => 'Address',
            'description' => '',
            'value'       => 'Company address',
            'field'       => '{"name":"value","label":"Value","type":"textarea"}',
            'active'      => 1
        ]);
        Setting::firstOrCreate([
            'key'         => 'policy'
        ],[
            'name'        => 'Policy',
            'description' => '',
            'value'       => '',
            'field'       => '{"name":"value","label":"Value","type":"textarea"}',
            'active'      => 1
        ]);
        Setting::firstOrCreate([
            'key'         => 'about'
        ],[
            'name'        => 'About',
            'description' => '',
            'value'       => '',
            'field'       => '{"name":"value","label":"Value","type":"textarea"}',
            'active'      => 1
        ]);
    }
}
