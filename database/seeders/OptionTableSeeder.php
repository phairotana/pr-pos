<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Option;
use Illuminate\Database\Seeder;

class OptionTableSeeder extends Seeder
{
    /**
    * Run the database seeds.
    * php artisan db:seed --class=OptionTableSeeder
     *
     * @return void
     */
    public function run()
    {
        /* create type purchase type options */
        Option::firstOrCreate(['value' => 'pending'], ['display' => 'Pending', 'type' => 'purchase_type', 'sort' => '502']);
        Option::firstOrCreate(['value' => 'receive'], ['display' => 'Receive', 'type' => 'purchase_type', 'sort' => '503']);
        Option::firstOrCreate(['value' => 'partial_receive'], ['display' => 'Partial Receive', 'type' => 'purchase_type', 'sort' => '504']);

        // Main Warehouse
        // Branch::firstOrCreate(['branch_name' => 'Phnom Penh', 'address' => 'Phnom Penh', 'main'=> true, 'status' => 'Active']);

        Option::firstOrCreate(['value' => 4100], ['display' => 'Currency format', 'type' => 'currency_kh' ]);
    }
}
