<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('payments')->insert([
            [
                'name' => 'Kredi Kartı',
                'gorsel' => '/assets/payments/kredi-karti.webp',
                'sira' => 1,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Payco',
                'gorsel' => '/assets/payments/payco.webp',
                'sira' => 2,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Parazula',
                'gorsel' => '/assets/payments/parazula.webp',
                'sira' => 3,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Papara',
                'gorsel' => '/assets/payments/papara.webp',
                'sira' => 4,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kripto',
                'gorsel' => '/assets/payments/kripto.webp',
                'sira' => 5,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Paypay',
                'gorsel' => '/assets/payments/paypay.webp',
                'sira' => 6,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
} 