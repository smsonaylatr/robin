<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OyunlarTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('oyunlar')->insert([
            'id' => '1',
            'gorsel' => '/img/betnowolympos.png',
            'url' => '/game.php?gameid=23003',
            'sira' => '9',
        ]);
        DB::table('oyunlar')->insert([
            'id' => '2',
            'gorsel' => '/img/betnoweus.png',
            'url' => '/game.php?gameid=23981',
            'sira' => '2',
        ]);
        DB::table('oyunlar')->insert([
            'id' => '3',
            'gorsel' => '/img/betnowbon.png',
            'url' => '/game.php?gameid=23982',
            'sira' => '3',
        ]);
        DB::table('oyunlar')->insert([
            'id' => '7',
            'gorsel' => '/img/b69473a6-b9ca-4ff0-a241-1ee1d46eab18.png',
            'url' => '/game.php?gameid=24095',
            'sira' => '4',
        ]);
        DB::table('oyunlar')->insert([
            'id' => '8',
            'gorsel' => '/img/iriscown_6810957a2f085.webp',
            'url' => '/game.php?gameid=23625',
            'sira' => '5',
        ]);
        DB::table('oyunlar')->insert([
            'id' => '10',
            'gorsel' => '/img/candycorner_6810a83ee5de8.webp',
            'url' => '/game.php?gameid=23724',
            'sira' => '5',
        ]);
        DB::table('oyunlar')->insert([
            'id' => '18',
            'gorsel' => '/img/starlight1000_6810956482e1c.webp',
            'url' => '/game.php?gameid=24097',
            'sira' => '8',
        ]);
        DB::table('oyunlar')->insert([
            'id' => '19',
            'gorsel' => '/img/betnowolympos.png',
            'url' => '/game.php?gameid=23002',
            'sira' => '1',
        ]);
    }
}
