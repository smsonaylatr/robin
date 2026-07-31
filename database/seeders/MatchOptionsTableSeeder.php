<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MatchOptionsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('match_options')->insert([
            'id' => '1',
            'eventid' => '95348671',
            'option_type' => 'MaÃ§ Sonucu',
            'option_value' => '1',
            'odds' => '1.85',
        ]);
        DB::table('match_options')->insert([
            'id' => '2',
            'eventid' => '48050955',
            'option_type' => 'Maç Sonucu',
            'option_value' => '1',
            'odds' => '4.32',
        ]);
        DB::table('match_options')->insert([
            'id' => '3',
            'eventid' => '48050955',
            'option_type' => 'Maç Sonucu',
            'option_value' => 'X',
            'odds' => '3.57',
        ]);
        DB::table('match_options')->insert([
            'id' => '4',
            'eventid' => '48050955',
            'option_type' => 'Maç Sonucu',
            'option_value' => '2',
            'odds' => '2.05',
        ]);
        DB::table('match_options')->insert([
            'id' => '5',
            'eventid' => '48050955',
            'option_type' => 'Çifte Şans',
            'option_value' => 'X1',
            'odds' => '12.00',
        ]);
        DB::table('match_options')->insert([
            'id' => '6',
            'eventid' => '48050955',
            'option_type' => 'Çifte Şans',
            'option_value' => '12',
            'odds' => '12.00',
        ]);
        DB::table('match_options')->insert([
            'id' => '7',
            'eventid' => '48050955',
            'option_type' => 'Çifte Şans',
            'option_value' => 'X2',
            'odds' => '12.00',
        ]);
        DB::table('match_options')->insert([
            'id' => '8',
            'eventid' => '48050955',
            'option_type' => 'Kırmızı Kart',
            'option_value' => 'Evet',
            'odds' => '25.00',
        ]);
    }
}
