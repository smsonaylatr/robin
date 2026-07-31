<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class YoneticilerTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('yoneticiler')->insert([
            'id' => '2',
            'kullanici_adi' => 'admin',
            'sifre' => '0192023a7bbd73250516f069df18b500',
            'eposta' => 'deneme@gmail.com',
            'yetki' => 'admin',
            'olusturulma_tarihi' => '2025-05-22 17:41:12',
        ]);
    }
}
