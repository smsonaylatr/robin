<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBultenTable extends Migration
{
    public function up()
    {
        Schema::create('bulten', function (Blueprint $table) {
            // Bu alanlar örnek olarak eklenmiştir. Gerçek alanları CREATE TABLE'dan parse etmelisiniz.
            $table->increments('id');
            $table->string('name')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bulten');
    }
}
