<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('home_sections', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Bölüm adı (örn: son_kazananlar, yaklasan_maclar, oyunlar, casino_oyunlari, canli_casinolar)
            $table->string('title'); // Görünen başlık (örn: Son Kazananlar, Yaklaşan Maçlar, Oyunlar, Casino Oyunları, Canlı Casinolar)
            $table->string('icon')->nullable(); // İkon SVG path
            $table->integer('order')->default(0); // Sıralama
            $table->boolean('is_active')->default(true); // Aktif/Pasif
            $table->boolean('is_required')->default(false); // Zorunlu bölüm (örn: slider)
            $table->timestamps();
        });

        // Varsayılan bölümleri ekle
        DB::table('home_sections')->insert([
            [
                'name' => 'slider',
                'title' => 'Slider',
                'icon' => null,
                'order' => 1,
                'is_active' => true,
                'is_required' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'son_kazananlar',
                'title' => 'Son Kazananlar',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-500"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"></path><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"></path><path d="M4 22h16"></path><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"></path><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"></path><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"></path></svg>',
                'order' => 2,
                'is_active' => true,
                'is_required' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'yaklasan_maclar',
                'title' => 'Yaklaşan Maçlar',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-500"><circle cx="12" cy="12" r="10"></circle><path d="m9 12 2 2 4-4"></path></svg>',
                'order' => 3,
                'is_active' => true,
                'is_required' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'oyunlar',
                'title' => 'Oyunlar',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-500"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"></rect><path d="M9 9h.01"></path><path d="M15 9h.01"></path><path d="M12 15h.01"></path></svg>',
                'order' => 4,
                'is_active' => true,
                'is_required' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'casino_oyunlari',
                'title' => 'Casino Oyunları',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-500"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"></rect><path d="M9 9h.01"></path><path d="M15 9h.01"></path><path d="M12 15h.01"></path></svg>',
                'order' => 5,
                'is_active' => true,
                'is_required' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'canli_casinolar',
                'title' => 'Canlı Casinolar',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-500"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"></rect><path d="M9 9h.01"></path><path d="M15 9h.01"></path><path d="M12 15h.01"></path></svg>',
                'order' => 6,
                'is_active' => true,
                'is_required' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_sections');
    }
}; 