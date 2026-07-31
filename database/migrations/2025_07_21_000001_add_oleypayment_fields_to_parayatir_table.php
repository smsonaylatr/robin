<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOleypaymentFieldsToParayatirTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('parayatir', function (Blueprint $table) {
            $table->string('transaction_id')->nullable()->after('aciklama');
            $table->string('payment_provider')->nullable()->after('transaction_id');
            $table->string('api_txn')->nullable()->after('payment_provider');
            $table->timestamp('api_callback_received_at')->nullable()->after('api_txn');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('parayatir', function (Blueprint $table) {
            $table->dropColumn(['transaction_id', 'payment_provider', 'api_txn', 'api_callback_received_at']);
        });
    }
} 