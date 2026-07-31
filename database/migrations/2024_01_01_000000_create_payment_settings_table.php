<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_settings', function (Blueprint $table) {
            $table->id();
            $table->string('setting_key')->unique();
            $table->text('setting_value');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Varsayılan ayarları ekle
        DB::table('payment_settings')->insert([
            [
                'setting_key' => 'hemen_havale_api_key',
                'setting_value' => '6777a612e26a6c803c7a9add',
                'description' => 'Hemen Havale API Key'
            ],
            [
                'setting_key' => 'mefete_api_key',
                'setting_value' => '6777a612e26a6c803c7a9ae0',
                'description' => 'Mefete API Key'
            ],
            [
                'setting_key' => 'papara_api_key',
                'setting_value' => '6777a612e26a6c803c7a9ae6',
                'description' => 'Papara API Key'
            ],
            [
                'setting_key' => 'hemen_parolapara_api_key',
                'setting_value' => '6777a612e26a6c803c7a9af2',
                'description' => 'Hemen Parola Para API Key'
            ],
            [
                'setting_key' => 'kredi_karti_api_key',
                'setting_value' => '66d7fc57f271aceccbf75e2a',
                'description' => 'Kredi Kartı API Key'
            ],
            [
                'setting_key' => 'hemen_kripto_api_key',
                'setting_value' => '67516c5e343e4e740556c060',
                'description' => 'Hemen Kripto API Key'
            ],
            [
                'setting_key' => 'extra_api_key',
                'setting_value' => 'apikey-08f43403-a238-4eeb-b1b6-a9dd379a1c70',
                'description' => 'Extra Cüzdan API Key'
            ],
            [
                'setting_key' => 'extra_secret',
                'setting_value' => 'c92539a2-4e6f-49c9-b7f3-6adbc3615695',
                'description' => 'Extra Cüzdan Secret Key'
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_settings');
    }
}; 