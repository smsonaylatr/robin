<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('registration_settings', function (Blueprint $table) {
            $table->id();
            $table->string('field_name')->unique();
            $table->string('field_label');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_required')->default(false);
            $table->integer('field_order')->default(0);
            $table->string('field_type')->default('text'); // text, email, tel, select, date, etc.
            $table->text('field_options')->nullable(); // JSON for select options
            $table->timestamps();
        });

        // Varsayılan kayıt form alanlarını ekle
        $defaultFields = [
            [
                'field_name' => 'firstName',
                'field_label' => 'Adınız',
                'is_active' => true,
                'is_required' => true,
                'field_order' => 1,
                'field_type' => 'text'
            ],
            [
                'field_name' => 'lastName',
                'field_label' => 'Soyadınız',
                'is_active' => true,
                'is_required' => true,
                'field_order' => 2,
                'field_type' => 'text'
            ],
            [
                'field_name' => 'tc',
                'field_label' => 'T.C. Kimlik No',
                'is_active' => true,
                'is_required' => true,
                'field_order' => 3,
                'field_type' => 'text'
            ],
            [
                'field_name' => 'birthDate',
                'field_label' => 'Doğum Tarihi',
                'is_active' => true,
                'is_required' => true,
                'field_order' => 4,
                'field_type' => 'date'
            ],
            [
                'field_name' => 'il',
                'field_label' => 'İl',
                'is_active' => true,
                'is_required' => true,
                'field_order' => 5,
                'field_type' => 'select'
            ],
            [
                'field_name' => 'ilce',
                'field_label' => 'İlçe',
                'is_active' => true,
                'is_required' => true,
                'field_order' => 6,
                'field_type' => 'text'
            ],
            [
                'field_name' => 'postakodu',
                'field_label' => 'Posta Kodu',
                'is_active' => true,
                'is_required' => false,
                'field_order' => 7,
                'field_type' => 'text'
            ],
            [
                'field_name' => 'username',
                'field_label' => 'Kullanıcı Adı',
                'is_active' => true,
                'is_required' => true,
                'field_order' => 8,
                'field_type' => 'text'
            ],
            [
                'field_name' => 'email',
                'field_label' => 'E-posta',
                'is_active' => true,
                'is_required' => true,
                'field_order' => 9,
                'field_type' => 'email'
            ],
            [
                'field_name' => 'phoneNumber',
                'field_label' => 'Telefon',
                'is_active' => true,
                'is_required' => true,
                'field_order' => 10,
                'field_type' => 'tel'
            ],
            [
                'field_name' => 'parabirimi',
                'field_label' => 'Para Birimi',
                'is_active' => true,
                'is_required' => true,
                'field_order' => 11,
                'field_type' => 'select'
            ]
        ];

        DB::table('registration_settings')->insert($defaultFields);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registration_settings');
    }
};