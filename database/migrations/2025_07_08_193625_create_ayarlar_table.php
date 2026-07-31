<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAyarlarTable extends Migration
{
    public function up()
    {
        Schema::create('ayarlar', function (Blueprint $table) {
            $table->increments('id');
            $table->string('casinolimit')->nullable();
            $table->string('site_adi')->nullable();
            $table->text('site_aciklama')->nullable();
            $table->text('site_kelimeler')->nullable();
            $table->text('footer_desc')->nullable();
            $table->integer('site_durum')->default(1);
            $table->integer('homespor')->default(1);
            $table->string('logo')->nullable(); // Logo dosya yolu
            $table->string('favicon')->nullable(); // Favicon dosya yolu
            $table->string('telegram')->nullable();
            $table->string('instagram')->nullable();
            $table->string('twitter')->nullable();
            $table->integer('smsadet')->default(0);
            $table->integer('dogrulama')->default(0);
            $table->integer('fakeapi')->default(0);
            $table->string('yoneticicode')->nullable();
            $table->text('canlidestek')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ayarlar');
    }
}
