<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParacekTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('paracek')->insert([
            'id' => '1',
            'uye' => '1001',
            'banka' => '1',
            'miktar' => '5000',
            'tarih' => '2025-07-04 00:00:52',
            'durum' => '1',
            'turu' => '1',
            'aciklama' => '1',
            'notttttt' => '1',
            'sube' => null,
            'hesap' => null,
            'iban' => null,
            'created_at' => '2025-07-04 00:00:52',
            'updated_at' => '2025-07-04 03:27:13',
            'firma_key' => null,
            'token' => null,
            'user_id' => null,
            'name' => null,
            'telefon' => null,
            'email' => null,
        ]);
    }
}
