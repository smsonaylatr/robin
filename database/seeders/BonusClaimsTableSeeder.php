<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BonusClaimsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('bonus_claims')->insert([
            'id' => '1',
            'user_id' => '589',
            'username' => 'Linaysem',
            'bonus_id' => '1',
            'bonus_name' => '250 TL Deneme',
            'bonus_amount' => '250.00',
            'claimed_at' => '2025-06-25 17:56:37',
        ]);
        DB::table('bonus_claims')->insert([
            'id' => '2',
            'user_id' => '17250',
            'username' => 'Teyraxerip21',
            'bonus_id' => '1',
            'bonus_name' => '250 TL Deneme',
            'bonus_amount' => '250.00',
            'claimed_at' => '2025-07-04 04:35:52',
        ]);
        DB::table('bonus_claims')->insert([
            'id' => '3',
            'user_id' => '1006',
            'username' => 'Ask3535',
            'bonus_id' => '1',
            'bonus_name' => '250 TL Deneme',
            'bonus_amount' => '250.00',
            'claimed_at' => '2025-06-26 08:55:18',
        ]);
        DB::table('bonus_claims')->insert([
            'id' => '4',
            'user_id' => '1059',
            'username' => 'tester',
            'bonus_id' => '1',
            'bonus_name' => '250 TL Deneme',
            'bonus_amount' => '250.00',
            'claimed_at' => '2025-06-26 16:31:53',
        ]);
        DB::table('bonus_claims')->insert([
            'id' => '5',
            'user_id' => '1059',
            'username' => 'tester',
            'bonus_id' => '4',
            'bonus_name' => '%25 Yatirim Bonusu',
            'bonus_amount' => '5000.00',
            'claimed_at' => '2025-06-26 21:35:41',
        ]);
        DB::table('bonus_claims')->insert([
            'id' => '6',
            'user_id' => '17251',
            'username' => 'Katangirl',
            'bonus_id' => '1',
            'bonus_name' => '250 TL Deneme',
            'bonus_amount' => '250.00',
            'claimed_at' => '2025-06-27 12:44:43',
        ]);
        DB::table('bonus_claims')->insert([
            'id' => '7',
            'user_id' => '108',
            'username' => 'Cemalerdem74',
            'bonus_id' => '1',
            'bonus_name' => '250 TL Deneme',
            'bonus_amount' => '250.00',
            'claimed_at' => '2025-06-27 13:04:07',
        ]);
    }
}
