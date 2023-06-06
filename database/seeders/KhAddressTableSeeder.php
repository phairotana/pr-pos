<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;


class KhAddressTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=KhAddressTableSeeder
     * @return void
     */
    public function run()
    {
        //
        Schema::dropIfExists('kh_address');

        $path = 'navi/address_v2.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('KhAddress table seeded!');

    }
}
