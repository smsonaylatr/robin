<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('admin')->insert([
            'id' => '101',
            'username' => 'Samat932',
            'password' => 'a98d010cd555a398d6a5aad68b20fd06',
            'name' => 'Kumsal yağmur Samat',
            'email' => 'abrahamx233@gmail.com',
            'bakiye' => '232.5',
            'parabirimi' => '₺',
            'durum' => '1',
            'sifresi' => 'Samat932',
            'telefon' => '05411812961',
            'postakodu' => '61000',
            'ulke' => '',
            'il' => 'Trabzon',
            'ilce' => 'Merkez',
            'dt' => '2002-05-24',
            'tc' => '28571376560',
            'spor' => '0',
            'casino' => '0',
            'cekim' => '0',
            '2factor' => '1',
            'aff' => '2',
            'bayisi' => '0',
            'songirisi' => null,
            'kayit_ip' => '172.70.228.179',
            'kayit_tarih' => '2025-06-06 00:13:57',
            'cevrim' => '1753',
            'songiris' => '2025-06-05 21:14:50',
            'songirisip' => '0',
        ]);
    }
}
