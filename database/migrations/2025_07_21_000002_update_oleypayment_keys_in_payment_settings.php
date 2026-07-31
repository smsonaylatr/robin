<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateOleypaymentKeysInPaymentSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add unified OleyPayment API keys
        DB::table('payment_settings')->insertOrIgnore([
            ['setting_key' => 'oley_api_key', 'setting_value' => ''],
            ['setting_key' => 'oley_secret', 'setting_value' => ''],
        ]);
        
        // Remove old individual keys if they exist
        DB::table('payment_settings')->whereIn('setting_key', [
            'kolayhavale_api_key',
            'kolayhavale_secret', 
            'parazula_api_key',
            'parazula_secret',
            'popy_api_key',
            'popy_secret',
            'payco_api_key',
            'payco_secret'
        ])->delete();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Re-add old individual keys
        DB::table('payment_settings')->insertOrIgnore([
            ['setting_key' => 'kolayhavale_api_key', 'setting_value' => ''],
            ['setting_key' => 'kolayhavale_secret', 'setting_value' => ''],
            ['setting_key' => 'parazula_api_key', 'setting_value' => ''],
            ['setting_key' => 'parazula_secret', 'setting_value' => ''],
            ['setting_key' => 'popy_api_key', 'setting_value' => ''],
            ['setting_key' => 'popy_secret', 'setting_value' => ''],
            ['setting_key' => 'payco_api_key', 'setting_value' => ''],
            ['setting_key' => 'payco_secret', 'setting_value' => ''],
        ]);
        
        // Remove unified keys
        DB::table('payment_settings')->whereIn('setting_key', [
            'oley_api_key',
            'oley_secret'
        ])->delete();
    }
} 