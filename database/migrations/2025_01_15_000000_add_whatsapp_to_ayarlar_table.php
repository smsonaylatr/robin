<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWhatsappToAyarlarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ayarlar', function (Blueprint $table) {
            $table->string('whatsapp')->nullable()->after('telegram');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ayarlar', function (Blueprint $table) {
            $table->dropColumn('whatsapp');
        });
    }
} 