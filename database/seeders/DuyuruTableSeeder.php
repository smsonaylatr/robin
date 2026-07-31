<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DuyuruTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('duyuru')->insert([
            'id' => '5',
            'resim' => '/images/duy_684b58b993fb7.png',
            'url' => '/bonus',
            'konu' => 'ETKİNLİK',
            'aciklama' => '%200 Çevrimsiz Nakit Yatırım Bonusu!

 1000₺ yatır, tam 3.000₺ anında hesabında!
 3000₺ yatır, tam 9.000₺ anında hesabında!

 Hiçbir çevrim şartı olmadan, paranı hemen çek!

 Şans ve Kazanç, OldenBet ile Yan Yana!',
            'created_at' => '2025-06-13 00:46:17',
        ]);
    }
}
