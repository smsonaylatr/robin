<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYoneticilerTable extends Migration
{
    public function up()
    {
        Schema::create('yoneticiler', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kullanici_adi')->unique();
            $table->string('sifre');
            $table->string('email')->nullable();
            $table->string('yetki')->default('admin');
            $table->string('remember_token')->nullable();
            $table->timestamp('olusturulma_tarihi')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('yoneticiler');
    }
}
