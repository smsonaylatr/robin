<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OddsTypesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('odds_types')->insert([
            'id' => '1',
            'name' => 'Ekstra Süre Üzer/Alt',
        ]);
        DB::table('odds_types')->insert([
            'id' => '2',
            'name' => '1x2 Ekstra Süre',
        ]);
        DB::table('odds_types')->insert([
            'id' => '3',
            'name' => 'Ekstra Süre Asya Köşe',
        ]);
        DB::table('odds_types')->insert([
            'id' => '4',
            'name' => 'Ekstra Süre Toplam Köşe (3 Yollar) (1. Yarı)',
        ]);
        DB::table('odds_types')->insert([
            'id' => '5',
            'name' => 'Ekstra Süre Çift Sonuç',
        ]);
        DB::table('odds_types')->insert([
            'id' => '6',
            'name' => 'Ekstra Sürede İlk Golü Hangi Takım Atar?',
        ]);
        DB::table('odds_types')->insert([
            'id' => '7',
            'name' => 'Ekstra Süre Asya Köşe (1. Yarı)',
        ]);
        DB::table('odds_types')->insert([
            'id' => '8',
            'name' => 'Zafer Yöntemi',
        ]);
        DB::table('odds_types')->insert([
            'id' => '9',
            'name' => 'Ekstra Sürede Her İki Takımın Da Gol Atması',
        ]);
        DB::table('odds_types')->insert([
            'id' => '10',
            'name' => 'Elemeye Geçme',
        ]);
        DB::table('odds_types')->insert([
            'id' => '11',
            'name' => 'Ekstra Süre Asya Handikap',
        ]);
        DB::table('odds_types')->insert([
            'id' => '12',
            'name' => '1x2 Ekstra Süre (1. Yarı)',
        ]);
        DB::table('odds_types')->insert([
            'id' => '13',
            'name' => 'Ekstra Süre Toplam Köşe (3 Yollar)',
        ]);
        DB::table('odds_types')->insert([
            'id' => '14',
            'name' => 'Ekstra Süre Üzer/Alt (1. Yarı)',
        ]);
        DB::table('odds_types')->insert([
            'id' => '15',
            'name' => 'Son Köşe',
        ]);
        DB::table('odds_types')->insert([
            'id' => '16',
            'name' => 'Deplasman Takımının Kaç Gol Atacağı?',
        ]);
        DB::table('odds_types')->insert([
            'id' => '17',
            'name' => 'Asya Handikap (1. Yarı)',
        ]);
        DB::table('odds_types')->insert([
            'id' => '18',
            'name' => 'İlk Gol Aralığında',
        ]);
        DB::table('odds_types')->insert([
            'id' => '19',
            'name' => '1x2 (1. Yarı)',
        ]);
        DB::table('odds_types')->insert([
            'id' => '20',
            'name' => 'Maç Köşeleri',
        ]);
        DB::table('odds_types')->insert([
            'id' => '21',
            'name' => '3-Yol Handikap',
        ]);
        DB::table('odds_types')->insert([
            'id' => '22',
            'name' => '1x2 - 30 dakika',
        ]);
        DB::table('odds_types')->insert([
            'id' => '23',
            'name' => 'Sonuç',
        ]);
        DB::table('odds_types')->insert([
            'id' => '24',
            'name' => 'Üzer/Alt Çizgisi (1. Yarı)',
        ]);
        DB::table('odds_types')->insert([
            'id' => '25',
            'name' => 'Maç Golleri',
        ]);
        DB::table('odds_types')->insert([
            'id' => '26',
            'name' => 'Avrupa Handikap (1. Yarı)',
        ]);
        DB::table('odds_types')->insert([
            'id' => '27',
            'name' => 'Ev Sahibi Takım 1. Yarıda Gol Atar mı?',
        ]);
        DB::table('odds_types')->insert([
            'id' => '28',
            'name' => 'Ev Sahibi Takım İki Yarıda Da Gol Atar mı?',
        ]);
        DB::table('odds_types')->insert([
            'id' => '29',
            'name' => 'Sonuç / Her İki Takımın Da Gol Atması',
        ]);
        DB::table('odds_types')->insert([
            'id' => '30',
            'name' => 'Her İki Takımın Da Gol Atması (1. Yarı)',
        ]);
        DB::table('odds_types')->insert([
            'id' => '31',
            'name' => 'Toplam Köşe (3 Yollar) (2. Yarı)',
        ]);
        DB::table('odds_types')->insert([
            'id' => '32',
            'name' => 'Asya Köşe',
        ]);
        DB::table('odds_types')->insert([
            'id' => '33',
            'name' => 'Asya Handikap',
        ]);
        DB::table('odds_types')->insert([
            'id' => '34',
            'name' => '1x2 - 40 dakika',
        ]);
        DB::table('odds_types')->insert([
            'id' => '35',
            'name' => '2. Yarıyı Kazanma',
        ]);
        DB::table('odds_types')->insert([
            'id' => '36',
            'name' => 'Üzer/Alt Çizgisi',
        ]);
        DB::table('odds_types')->insert([
            'id' => '37',
            'name' => 'Toplam Köşe',
        ]);
        DB::table('odds_types')->insert([
            'id' => '38',
            'name' => 'Deplasman Takımının İki Yarıda Da Gol Atması',
        ]);
        DB::table('odds_types')->insert([
            'id' => '39',
            'name' => 'Deplasman Takımının Golleri',
        ]);
        DB::table('odds_types')->insert([
            'id' => '40',
            'name' => 'Toplam Köşe (3 Yollar) (1. Yarı)',
        ]);
        DB::table('odds_types')->insert([
            'id' => '41',
            'name' => '1x2 - 50 dakika',
        ]);
        DB::table('odds_types')->insert([
            'id' => '42',
            'name' => '3. Köşe Yarışı?',
        ]);
        DB::table('odds_types')->insert([
            'id' => '43',
            'name' => 'Her İki Takımın Da Gol Atması (2. Yarı)',
        ]);
        DB::table('odds_types')->insert([
            'id' => '44',
            'name' => '9. Köşe Yarışı?',
        ]);
        DB::table('odds_types')->insert([
            'id' => '45',
            'name' => '7. Köşe Yarışı?',
        ]);
        DB::table('odds_types')->insert([
            'id' => '46',
            'name' => 'Gol Atıcı',
        ]);
        DB::table('odds_types')->insert([
            'id' => '47',
            'name' => 'Deplasman 1. Gol Aralığında',
        ]);
        DB::table('odds_types')->insert([
            'id' => '48',
            'name' => 'Beraberlik Yok Bahis',
        ]);
        DB::table('odds_types')->insert([
            'id' => '49',
            'name' => 'Üzer/Alt (1. Yarı)',
        ]);
        DB::table('odds_types')->insert([
            'id' => '50',
            'name' => '1x2 - 60 dakika',
        ]);
        DB::table('odds_types')->insert([
            'id' => '51',
            'name' => 'Asya Köşe (1. Yarı)',
        ]);
        DB::table('odds_types')->insert([
            'id' => '52',
            'name' => '1x2 - 80 dakika',
        ]);
        DB::table('odds_types')->insert([
            'id' => '53',
            'name' => '2 veya Daha Fazla Gol Atar mı?',
        ]);
        DB::table('odds_types')->insert([
            'id' => '54',
            'name' => 'Ev Sahibi 1. Gol Aralığında',
        ]);
        DB::table('odds_types')->insert([
            'id' => '55',
            'name' => 'Doğru Skor (1. Yarı)',
        ]);
        DB::table('odds_types')->insert([
            'id' => '56',
            'name' => '1x2 - 70 dakika',
        ]);
        DB::table('odds_types')->insert([
            'id' => '57',
            'name' => 'Deplasman Takımının Temiz Çarptığı',
        ]);
        DB::table('odds_types')->insert([
            'id' => '58',
            'name' => 'Ev Sahibi Takımın Golleri',
        ]);
        DB::table('odds_types')->insert([
            'id' => '59',
            'name' => 'Maç Sonucu',
        ]);
        DB::table('odds_types')->insert([
            'id' => '60',
            'name' => '3 veya Daha Fazla Gol Atar mı?',
        ]);
        DB::table('odds_types')->insert([
            'id' => '61',
            'name' => '5. Köşe Yarışı?',
        ]);
        DB::table('odds_types')->insert([
            'id' => '62',
            'name' => 'Son Golcü (3 Yollar)',
        ]);
        DB::table('odds_types')->insert([
            'id' => '63',
            'name' => 'Her Zaman Gol Atıcı',
        ]);
        DB::table('odds_types')->insert([
            'id' => '64',
            'name' => 'Yarı Sonu / Tam Süre',
        ]);
        DB::table('odds_types')->insert([
            'id' => '65',
            'name' => 'Sonraki 10 Dakikada Toplam',
        ]);
        DB::table('odds_types')->insert([
            'id' => '66',
            'name' => 'Ev Sahibi Takım Temiz Çarptığı',
        ]);
        DB::table('odds_types')->insert([
            'id' => '67',
            'name' => 'Ev Sahibi Takımının Kaç Gol Atacağı?',
        ]);
        DB::table('odds_types')->insert([
            'id' => '68',
            'name' => 'Goller Tek/Çift',
        ]);
        DB::table('odds_types')->insert([
            'id' => '69',
            'name' => 'Her İki Takımın Da Gol Atması',
        ]);
        DB::table('odds_types')->insert([
            'id' => '70',
            'name' => 'Deplasman Takımının 2. Yarıda Gol Atması',
        ]);
        DB::table('odds_types')->insert([
            'id' => '71',
            'name' => 'Hangi Takım 4. Köşeyi Atar? (2 Yollar)',
        ]);
        DB::table('odds_types')->insert([
            'id' => '72',
            'name' => 'Çift Şans',
        ]);
        DB::table('odds_types')->insert([
            'id' => '73',
            'name' => 'Hangi Takım İlk Golü Atar?',
        ]);
        DB::table('odds_types')->insert([
            'id' => '74',
            'name' => 'Hangi Takım 3. Köşeyi Atar? (2 Yollar)',
        ]);
        DB::table('odds_types')->insert([
            'id' => '75',
            'name' => 'Hangi Takım 2. Köşeyi Atar? (2 Yollar)',
        ]);
        DB::table('odds_types')->insert([
            'id' => '76',
            'name' => 'Köşe Avrupa Handikap',
        ]);
        DB::table('odds_types')->insert([
            'id' => '77',
            'name' => '1x2 - 10 dakika',
        ]);
        DB::table('odds_types')->insert([
            'id' => '78',
            'name' => 'Köşe 1x2',
        ]);
        DB::table('odds_types')->insert([
            'id' => '79',
            'name' => '1x2 - 20 dakika',
        ]);
        DB::table('odds_types')->insert([
            'id' => '80',
            'name' => 'İlk Gol Yöntemi',
        ]);
        DB::table('odds_types')->insert([
            'id' => '81',
            'name' => 'Elemeye Geçme Yöntemi',
        ]);
        DB::table('odds_types')->insert([
            'id' => '82',
            'name' => 'Penaltı Atışları Sonrası Maçı Kazanma',
        ]);
        DB::table('odds_types')->insert([
            'id' => '83',
            'name' => 'Ekstra Sürede Maçı Kazanma',
        ]);
        DB::table('odds_types')->insert([
            'id' => '84',
            'name' => 'Hangi Takım 2. Golü Atar?',
        ]);
        DB::table('odds_types')->insert([
            'id' => '85',
            'name' => 'Hangi Takım 2. Golü Atar?',
        ]);
        DB::table('odds_types')->insert([
            'id' => '86',
            'name' => 'Hangi Takım 6. Köşeyi Atar? (2 Yollar)',
        ]);
        DB::table('odds_types')->insert([
            'id' => '87',
            'name' => 'Hangi Takım 5. Köşeyi Atar? (2 Yollar)',
        ]);
        DB::table('odds_types')->insert([
            'id' => '88',
            'name' => 'Hangi Takım 7. Köşeyi Atar? (2 Yollar)',
        ]);
        DB::table('odds_types')->insert([
            'id' => '89',
            'name' => 'Hangi Takım 9. Köşeyi Atar? (2 Yollar)',
        ]);
        DB::table('odds_types')->insert([
            'id' => '90',
            'name' => '2. Gol Aralığında',
        ]);
        DB::table('odds_types')->insert([
            'id' => '91',
            'name' => 'Deplasman 2. Gol Aralığında',
        ]);
        DB::table('odds_types')->insert([
            'id' => '92',
            'name' => 'Hangi Takım 3. Golü Atar?',
        ]);
        DB::table('odds_types')->insert([
            'id' => '93',
            'name' => 'Hangi Takım 10. Köşeyi Atar? (2 Yollar)',
        ]);
        DB::table('odds_types')->insert([
            'id' => '94',
            'name' => '3. Gol Aralığında',
        ]);
        DB::table('odds_types')->insert([
            'id' => '95',
            'name' => 'Ev Sahibi 2. Gol Aralığında',
        ]);
        DB::table('odds_types')->insert([
            'id' => '96',
            'name' => 'Asya Handikaplı Çevrilen Penaltılar',
        ]);
        DB::table('odds_types')->insert([
            'id' => '97',
            'name' => 'Ani Ölüm',
        ]);
        DB::table('odds_types')->insert([
            'id' => '98',
            'name' => 'Deplasman Penaltı Atışları',
        ]);
        DB::table('odds_types')->insert([
            'id' => '99',
            'name' => 'Ev Sahibi Penaltı Atışları',
        ]);
        DB::table('odds_types')->insert([
            'id' => '100',
            'name' => 'Ev Sahibi Toplam Çevrilen Penaltılar',
        ]);
        DB::table('odds_types')->insert([
            'id' => '101',
            'name' => 'Toplam Penaltılar Atışları',
        ]);
        DB::table('odds_types')->insert([
            'id' => '102',
            'name' => 'Son Penaltı Skor/Eklenme',
        ]);
        DB::table('odds_types')->insert([
            'id' => '103',
            'name' => 'Doğru Skor Penaltı Atışlarında',
        ]);
        DB::table('odds_types')->insert([
            'id' => '104',
            'name' => 'Toplam Çevrilen Penaltılar',
        ]);
        DB::table('odds_types')->insert([
            'id' => '105',
            'name' => 'Toplam Çevrilen Penaltılar - Kalın Çizgi',
        ]);
        DB::table('odds_types')->insert([
            'id' => '106',
            'name' => 'Deplasman Toplam Çevrilen Penaltılar',
        ]);
        DB::table('odds_types')->insert([
            'id' => '107',
            'name' => 'Penaltı Atışları Kazananı',
        ]);
        DB::table('odds_types')->insert([
            'id' => '108',
            'name' => 'Hangi Takım 11. Köşeyi Atar? (2 Yollar)',
        ]);
        DB::table('odds_types')->insert([
            'id' => '109',
            'name' => 'Hangi Takım 4. Golü Atar?',
        ]);
        DB::table('odds_types')->insert([
            'id' => '110',
            'name' => 'Hangi Takım 8. Köşeyi Atar? (2 Yollar)',
        ]);
        DB::table('odds_types')->insert([
            'id' => '111',
            'name' => 'Son Penaltı Atışı Skorer (Penaltı Atışlarında)',
        ]);
        DB::table('odds_types')->insert([
            'id' => '112',
            'name' => 'Hangi Takım 5. Golü Atar?',
        ]);
        DB::table('odds_types')->insert([
            'id' => '113',
            'name' => '2. Gol Yöntemi',
        ]);
        DB::table('odds_types')->insert([
            'id' => '114',
            'name' => 'Hangi Takım 13. Köşeyi Atar? (2 Yollar)',
        ]);
        DB::table('odds_types')->insert([
            'id' => '115',
            'name' => 'Oyuncu Kartı',
        ]);
        DB::table('odds_types')->insert([
            'id' => '116',
            'name' => 'Sonraki 1 Dakikada Eylem',
        ]);
        DB::table('odds_types')->insert([
            'id' => '117',
            'name' => 'Sonraki 5 Dakikada İlk Eylem',
        ]);
        DB::table('odds_types')->insert([
            'id' => '118',
            'name' => 'Oyuncu Atılması',
        ]);
        DB::table('odds_types')->insert([
            'id' => '119',
            'name' => 'Toplam Kartlar',
        ]);
        DB::table('odds_types')->insert([
            'id' => '120',
            'name' => 'Hangi Takım 12. Köşeyi Atar? (2 Yollar)',
        ]);
        DB::table('odds_types')->insert([
            'id' => '121',
            'name' => 'Hangi Takım 14. Köşeyi Atar? (2 Yollar)',
        ]);
        DB::table('odds_types')->insert([
            'id' => '122',
            'name' => 'Hangi Takım 15. Köşeyi Atar? (2 Yollar)',
        ]);
        DB::table('odds_types')->insert([
            'id' => '123',
            'name' => 'Hangi Takım 16. Köşeyi Atar? (2 Yollar)',
        ]);
        DB::table('odds_types')->insert([
            'id' => '124',
            'name' => 'Hangi Takım 17. Köşeyi Atar? (2 Yollar)',
        ]);
        DB::table('odds_types')->insert([
            'id' => '125',
            'name' => 'Ev Sahibi 3. Gol Aralığında',
        ]);
        DB::table('odds_types')->insert([
            'id' => '126',
            'name' => 'Hangi Takım 18. Köşeyi Atar? (2 Yollar)',
        ]);
        DB::table('odds_types')->insert([
            'id' => '127',
            'name' => 'Hangi Takım 19. Köşeyi Atar? (2 Yollar)',
        ]);
        DB::table('odds_types')->insert([
            'id' => '128',
            'name' => 'Hangi Takım 20. Köşeyi Atar? (2 Yollar)',
        ]);
    }
}
