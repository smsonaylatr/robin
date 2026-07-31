<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SliderTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('slider')->insert([
            'id' => '1',
            'gorsel' => '/img/m11.png',
            'url' => '',
            'sira' => '1',
        ]);
        DB::table('slider')->insert([
            'id' => '4',
            'gorsel' => '/img/m12.png',
            'url' => '',
            'sira' => '2',
        ]);
        DB::table('slider')->insert([
            'id' => '5',
            'gorsel' => '/img/m13.png',
            'url' => '',
            'sira' => '3',
        ]);
    }
}
