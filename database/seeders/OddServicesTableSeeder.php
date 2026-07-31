<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OddServicesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('odd_services')->insert([
            'id' => '3',
            'betsapitechkey' => 'vpWAhWFj4RyV6RwIPzGcOUgSo',
            'name' => 'betsapi.tech',
            'list' => 'https://betsapi.tech/api/livesports.php?token=betsapitechv4',
            'odds' => 'https://betsapi.tech/api/liveodds.php?token=betsapitechv4&matchid=',
            'active' => '1',
            'created_at' => '2023-10-28 15:52:52',
            'updated_at' => '2025-03-20 00:15:57',
        ]);
        DB::table('odd_services')->insert([
            'id' => '4',
            'betsapitechkey' => 'betcasinoservices.com',
            'name' => 'betcasinoservices.com',
            'list' => 'https://betcasinoservices.com/api/livesports.php?token=betcasinoservices',
            'odds' => 'https://betcasinoservices.com/api/liveodds.php?token=betcasinoservices&matchid=',
            'active' => '0',
            'created_at' => '2023-10-28 15:52:52',
            'updated_at' => '2025-01-29 23:50:07',
        ]);
    }
}
