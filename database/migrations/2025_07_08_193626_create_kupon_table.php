<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKuponTable extends Migration
{
    public function up()
    {
        Schema::create('kupon', function (Blueprint $table) {
            // Bu alanlar örnek olarak eklenmiştir. Gerçek alanları CREATE TABLE'dan parse etmelisiniz.
            $table->increments('id');
            $table->string('name')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kupon');
    }
}
