<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CanliCasinoTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('canli_casino')->insert([
            'id' => '10',
            'gorsel' => '/img/aviator_68109808c98f9.webp',
            'url' => '/game.php?gameid=8779',
            'sira' => '1',
        ]);
        DB::table('canli_casino')->insert([
            'id' => '12',
            'gorsel' => '/img/avatairix_6810a66d783dc.webp',
            'url' => '/game.php?gameid=23243',
            'sira' => '2',
        ]);
        DB::table('canli_casino')->insert([
            'id' => '13',
            'gorsel' => '/img/spaceman_68109de2cb460.webp',
            'url' => '/game.php?gameid=18832',
            'sira' => '3',
        ]);
        DB::table('canli_casino')->insert([
            'id' => '14',
            'gorsel' => '/img/zeppelin_68109bd732a16.webp',
            'url' => '/game.php?gameid=8567',
            'sira' => '4',
        ]);
        DB::table('canli_casino')->insert([
            'id' => '15',
            'gorsel' => '/img/shakerclub_6810a306b87e3.webp',
            'url' => '/game.php?gameid=8773',
            'sira' => '5',
        ]);
        DB::table('canli_casino')->insert([
            'id' => '16',
            'gorsel' => '/img/avatairix_6810a66d783dc.webp',
            'url' => '/game.php?gameid=23789',
            'sira' => '6',
        ]);
    }
}
