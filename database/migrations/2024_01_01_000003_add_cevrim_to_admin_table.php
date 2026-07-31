<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('admin', function (Blueprint $table) {
            $table->decimal('cevrim', 10, 2)->default(0)->after('bakiye');
        });
    }

    public function down()
    {
        Schema::table('admin', function (Blueprint $table) {
            $table->dropColumn('cevrim');
        });
    }
}; 