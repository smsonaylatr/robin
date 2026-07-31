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
        // Update footer_desc with the correct copyright text
        DB::table('ayarlar')->where('id', 1)->update([
            'footer_desc' => "© 2025 BetNow. Tüm hakları saklıdır.\n\nBetNow, Curacao eGaming tarafından lisanslanmıştır."
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original footer_desc
        DB::table('ayarlar')->where('id', 1)->update([
            'footer_desc' => 'OLDENBET, sizlere farklı spor dallarından canlı bahis keyfi, en yeni slot oyunları ve çok daha fazlasını sunuyor. Yüksek oranlarla zengin bir oyun deneyimi için bize katılın'
        ]);
    }
};
