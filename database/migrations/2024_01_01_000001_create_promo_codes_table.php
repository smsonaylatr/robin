<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->decimal('amount', 10, 2); // Promosyon miktarı
            $table->integer('max_uses'); // Maksimum kullanım sayısı
            $table->integer('used_count')->default(0); // Kullanılan sayı
            $table->integer('turnover_multiplier'); // Çevrim katı (örn: 5x)
            $table->boolean('is_active')->default(true);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('promo_codes');
    }
}; 