<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MessagesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('messages')->insert([
            'id' => '2',
            'uye' => '0',
            'mesajturu' => 'Finans',
            'baslik' => 'test',
            'icerik' => 'Merhaba
Bu Test mesajıdır',
            'goruldu' => '1',
            'created_at' => '2025-05-20 16:27:56',
        ]);
    }
}
