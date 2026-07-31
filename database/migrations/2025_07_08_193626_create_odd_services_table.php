<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOddServicesTable extends Migration
{
    public function up()
    {
        Schema::create('odd_services', function (Blueprint $table) {
            // Bu alanlar örnek olarak eklenmiştir. Gerçek alanları CREATE TABLE'dan parse etmelisiniz.
            $table->increments('id');
            $table->string('name')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('odd_services');
    }
}
