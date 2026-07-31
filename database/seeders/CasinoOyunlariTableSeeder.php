<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CasinoOyunlariTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('casino_oyunlari')->insert([
            'id' => '15',
            'gorsel' => '/img/aztecgems_68108aef4d0b9.webp',
            'url' => '/games.php?gameid=23679',
            'sira' => '1',
        ]);
        DB::table('casino_oyunlari')->insert([
            'id' => '16',
            'gorsel' => '/img/bandit_68188fb93245d.webp',
            'url' => '/games.php?gameid=23002',
            'sira' => '2',
        ]);
        DB::table('casino_oyunlari')->insert([
            'id' => '17',
            'gorsel' => '/img/bibass1000_68188f8cd7896.webp',
            'url' => '/games.php?gameid=23100',
            'sira' => '3',
        ]);
        DB::table('casino_oyunlari')->insert([
            'id' => '18',
            'gorsel' => '/img/gatesnow_68107dde095c5.webp',
            'url' => '/games.php?gameid=23007',
            'sira' => '4',
        ]);
        DB::table('casino_oyunlari')->insert([
            'id' => '19',
            'gorsel' => '/img/iriscown_681090e407546.webp',
            'url' => '/games.php?gameid=23394',
            'sira' => '5',
        ]);
        DB::table('casino_oyunlari')->insert([
            'id' => '20',
            'gorsel' => '/img/starlight1000_6810859462e22.webp',
            'url' => '/games.php?gameid=23662',
            'sira' => '6',
        ]);
        DB::table('casino_oyunlari')->insert([
            'id' => '21',
            'gorsel' => '/img/sugar1000_681087a9de894.webp',
            'url' => '/games.php?gameid=39008',
            'sira' => '7',
        ]);
        DB::table('casino_oyunlari')->insert([
            'id' => '22',
            'gorsel' => '/img/super_68188e3bd0b4c.webp',
            'url' => '/games.php?gameid=23284',
            'sira' => '8',
        ]);
        DB::table('casino_oyunlari')->insert([
            'id' => '23',
            'gorsel' => '/img/sweet100_68108245065d3.webp',
            'url' => '/games.php?gameid=23501',
            'sira' => '9',
        ]);
        DB::table('casino_oyunlari')->insert([
            'id' => '24',
            'gorsel' => '/img/sweetdüz_68108dc12ebe8.webp',
            'url' => '/games.php?gameid=23656',
            'sira' => '10',
        ]);
    }
}
