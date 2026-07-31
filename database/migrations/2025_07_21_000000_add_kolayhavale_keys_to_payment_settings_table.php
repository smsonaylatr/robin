<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddKolayhavaleKeysToPaymentSettingsTable extends Migration
{
    public function up()
    {
        // Add kolayhavale_api_key and kolayhavale_secret keys with empty default values
        DB::table('payment_settings')->insertOrIgnore([
            ['setting_key' => 'kolayhavale_api_key', 'setting_value' => ''],
            ['setting_key' => 'kolayhavale_secret', 'setting_value' => ''],
        ]);
    }

    public function down()
    {
        DB::table('payment_settings')->whereIn('setting_key', ['kolayhavale_api_key', 'kolayhavale_secret'])->delete();
    }
}
