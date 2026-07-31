<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BonusesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('bonuses')->insert([
            'id' => '1',
            'bonus_name' => '500TL Deneme',
            'bonus_image' => '/images/250tldeneme.png',
            'bonus_description' => '500TL Deneme Bonusu
Bakiyenizi 2500 TL Yaparak  250 TL Çekim Talebinde Bulunabilirsiniz',
            'bonus_amount' => '500.00',
            'maxtutar' => '500',
            'deneme' => '1',
            'hosgeldin' => '0',
            'yuzde' => '0',
            'cevrim' => '10',
            'kayip' => '0',
            'yatirim' => '0',
            'altlimit' => '0',
            'created_at' => '2025-05-20 19:47:47',
            'aktif' => '1',
        ]);
        DB::table('bonuses')->insert([
            'id' => '2',
            'bonus_name' => '%200 Hosgeldin Bonusu',
            'bonus_image' => '/images/hosgeldinbonusu.png',
            'bonus_description' => 'Etkinlik Kural ve Şartları:

★ Sitemize Kredi kartı yöntemi ile yatırım yapan üyelerimiz, promosyonlar sayfamızda yer alan çalışmalarımızdan yararlanamazlar.

★ Bu promosyon başka bir promosyonla birleştirilemez. Bu bonustan yararlanan üyeler, kayıp bonusundan yararlanamazlar.

★ Oyun içi puan ve sembol biriktirme opsiyonu olan oyunlar bonus kapsamı dışındadır, bu opsiyonlar ile elde edilen kazançlar için kalan bonus çevriminin tamamlanması gerekmektedir.

★ Bonus paketi yalnızca belirtilen geçerli slot oyunlarına yapılacak ilk 3 yatırım için geçerlidir. Bonusu talep edebilmesi için oyuncunun her seferinde en az 100 TL yatırım yapmış olması gerekmektedir.

★ Promosyondan yararlanmak isteyen üyelerimizin profillerinde yer alan kişisel bilgilerim kısmının doğru ve eksiksiz olarak doldurulmuş olması gerekmektedir.

Geçerli Slotlar: Aşağıdaki sağlayıcılara ait slotlar ve belirtilen alanlar haricindeki tüm slotlarda geçerlidir.
(Wazdan, Playtech, Playson, Red tiger, Evoplay, Ruby Play, Yggdrasil sağlayıcılarına ait slotlarda ve canlı casino, sanal oyunlar, masa oyunları)

Yatırımlarınızın karşılığı olarak yararlanılabilecek bonus paketi şu şekildedir;

1. yatırım için; 5.000 TL\'ye kadar %100 Bonus + 80 Adet Gates Of Sekabet Freespin*
2. yatırım için; 6.000 TL\'ye kadar %100 Bonus + 90 Adet Gates Of Sekabet Freespin*
3. yatırım için; 9.000 TL\'ye kadar %100 Bonus + 100 Adet Gates Of Sekabet Freespin*
*FREESPIN BET DEĞERİ 5 TL\'dir

★ Freespinler oyuncunun belirtilen aşamalarda bonus üst sınırı kadar tutardan miktarından yararlanması durumunda ekstra olarak eklenecektir.
★ Promosyonun 2. ve 3. aşamaları, ilk yatırımdan sonraki 48 saat içerisinde yapılacak yeni yatırımlar dahilinde talep edilmelidir. Aksi takdirde kullanıcı promosyondan yararlanamaz.
★ İlk üç yatırım arasında herhangi bir çekim işlemi olmaması gerekmektedir. Çekim işlemi yapan üyeler sonraki aşamalardan faydalanamaz.
★ Para yatırma işlemi yapıldıktan sonra, bakiye kullanılmadan ‘’BONUS TALEP’\' sekmesinden bonus için talep oluşturulmalıdır. Hediye bonus hesaba tanımlandıktan sonra çevirimin üye profilindeki Bonus sayfasından takip edilebilir. Yatırım tutarı kullanıldıktan sonra katılımcı bonus talebinde bulunamaz.
★ Bonus çevrim katsayısı, yatırım ve bonus miktarının 20 katıdır. Örneğin; 500 TL yatırım için almış olduğunuz 500 TL bonusu diğer alanlarda kullanabilmek veya çekim yapabilmek için 1.000x20=20.000 TL çevrim şartı vardır.
★ Promosyon 06.08.2024\'ten itibaren ilk yatırımını yapan yeni üyeler için geçerlidir.
★ Bonus bakiyesi 1 TL\'nin altına düştüğü anda bonus otomatik olarak iptal edilir.
★ Her kullanıcı /IP/ e-mail adresi bu bonustan yalnızca 1 defa faydalanabilir.
★ Oyuncunun bonusları kötüye kullandığı ya da kural dışı hareketlerde bulunduğu tespit edilir ise, Sekabet söz konusu bonusları ve tüm kazançları iptal etme hakkına sahiptir.
★ Sekabet gerekli gördüğü takdirde üyelerinden kimlik, ikametgah vb. gibi belgeleri isteyebilir. Bu belgelerin kullanıcı tarafından ibraz edilmemesi durumunda Sekabet oyuncunun kazançları bloke etme, bonusu haber vermeksizin iptal etme, geri çekme ayrıca promosyon kural ve şartlarını değiştirme hakkını saklı tutar.
★ Promosyon kural ve şartlarına ek olarak sitemizin genel kural ve şartları da dahildir.',
            'bonus_amount' => '0.00',
            'maxtutar' => '0',
            'deneme' => '0',
            'hosgeldin' => '1',
            'yuzde' => '200',
            'cevrim' => '15',
            'kayip' => '0',
            'yatirim' => '0',
            'altlimit' => '0',
            'created_at' => '2025-05-20 19:47:47',
            'aktif' => '1',
        ]);
        DB::table('bonuses')->insert([
            'id' => '3',
            'bonus_name' => 'Kayip Bonusu',
            'bonus_image' => '/images/kayipbonusu.png',
            'bonus_description' => 'Kayıp bonusu %25',
            'bonus_amount' => '0.00',
            'maxtutar' => '0',
            'deneme' => '0',
            'hosgeldin' => '0',
            'yuzde' => '25',
            'cevrim' => '1',
            'kayip' => '1',
            'yatirim' => '0',
            'altlimit' => '0',
            'created_at' => '2025-05-20 19:47:47',
            'aktif' => '1',
        ]);
        DB::table('bonuses')->insert([
            'id' => '4',
            'bonus_name' => '%25 Yatirim Bonusu',
            'bonus_image' => '/images/25yatirim.png',
            'bonus_description' => '%25 Slot Yatırım Bonusu
 Çevrim Şartı  Ana Para + Bonus Miktarının 7 Katı',
            'bonus_amount' => '0.00',
            'maxtutar' => '0',
            'deneme' => '0',
            'hosgeldin' => '0',
            'yuzde' => '25',
            'cevrim' => '0',
            'kayip' => '0',
            'yatirim' => '1',
            'altlimit' => '0',
            'created_at' => '2025-05-20 19:47:47',
            'aktif' => '1',
        ]);
    }
}
