<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCanliCasinoTable extends Migration
{
    public function up()
    {
        Schema::create('canli_casino', function (Blueprint $table) {
            $table->increments('id');
            $table->string('gorsel')->nullable(); // Görsel URL'si
            $table->string('url')->nullable(); // Tıklanınca gidilecek URL
            $table->integer('sira')->default(0); // Sıralama
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('canli_casino');
    }
}
