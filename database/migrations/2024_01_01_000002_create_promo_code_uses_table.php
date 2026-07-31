<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('promo_code_uses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promo_code_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->decimal('amount_received', 10, 2);
            $table->decimal('turnover_required', 10, 2);
            $table->timestamps();
            
            // Bir kullanıcı bir promosyon kodunu sadece bir kez kullanabilir
            $table->unique(['promo_code_id', 'user_id']);
            
            // Foreign key constraint'i manuel olarak ekle
            $table->foreign('user_id')->references('id')->on('admin')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('promo_code_uses');
    }
}; 