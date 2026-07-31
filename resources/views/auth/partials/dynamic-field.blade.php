@if($field->is_active)
<div>
    <label for="{{ $field->field_name }}" class="block text-sm font-medium text-zinc-300 mb-2">
        {{ $field->field_label }}
        @if($field->is_required) <span class="text-red-500">*</span> @endif
    </label>
    
    @if($field->field_type === 'select')
        @if($field->field_name === 'il')
            <!-- İl dropdown -->
            <select id="{{ $field->field_name }}" name="{{ $field->field_name }}" 
                    @if($field->is_required) required @endif
                    class="w-full px-4 py-3 bg-zinc-800/50 border border-zinc-700/50 rounded-lg text-white focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-all duration-200">
                <option value="">İl seçiniz</option>
                <option value="Adana" @if(old('il') == 'Adana') selected @endif>Adana</option>
                <option value="Adıyaman" @if(old('il') == 'Adıyaman') selected @endif>Adıyaman</option>
                <option value="Afyonkarahisar" @if(old('il') == 'Afyonkarahisar') selected @endif>Afyonkarahisar</option>
                <option value="Ağrı" @if(old('il') == 'Ağrı') selected @endif>Ağrı</option>
                <option value="Amasya" @if(old('il') == 'Amasya') selected @endif>Amasya</option>
                <option value="Ankara" @if(old('il') == 'Ankara') selected @endif>Ankara</option>
                <option value="Antalya" @if(old('il') == 'Antalya') selected @endif>Antalya</option>
                <option value="Artvin" @if(old('il') == 'Artvin') selected @endif>Artvin</option>
                <option value="Aydın" @if(old('il') == 'Aydın') selected @endif>Aydın</option>
                <option value="Balıkesir" @if(old('il') == 'Balıkesir') selected @endif>Balıkesir</option>
                <option value="Bilecik" @if(old('il') == 'Bilecik') selected @endif>Bilecik</option>
                <option value="Bingöl" @if(old('il') == 'Bingöl') selected @endif>Bingöl</option>
                <option value="Bitlis" @if(old('il') == 'Bitlis') selected @endif>Bitlis</option>
                <option value="Bolu" @if(old('il') == 'Bolu') selected @endif>Bolu</option>
                <option value="Burdur" @if(old('il') == 'Burdur') selected @endif>Burdur</option>
                <option value="Bursa" @if(old('il') == 'Bursa') selected @endif>Bursa</option>
                <option value="Çanakkale" @if(old('il') == 'Çanakkale') selected @endif>Çanakkale</option>
                <option value="Çankırı" @if(old('il') == 'Çankırı') selected @endif>Çankırı</option>
                <option value="Çorum" @if(old('il') == 'Çorum') selected @endif>Çorum</option>
                <option value="Denizli" @if(old('il') == 'Denizli') selected @endif>Denizli</option>
                <option value="Diyarbakır" @if(old('il') == 'Diyarbakır') selected @endif>Diyarbakır</option>
                <option value="Edirne" @if(old('il') == 'Edirne') selected @endif>Edirne</option>
                <option value="Elazığ" @if(old('il') == 'Elazığ') selected @endif>Elazığ</option>
                <option value="Erzincan" @if(old('il') == 'Erzincan') selected @endif>Erzincan</option>
                <option value="Erzurum" @if(old('il') == 'Erzurum') selected @endif>Erzurum</option>
                <option value="Eskişehir" @if(old('il') == 'Eskişehir') selected @endif>Eskişehir</option>
                <option value="Gaziantep" @if(old('il') == 'Gaziantep') selected @endif>Gaziantep</option>
                <option value="Giresun" @if(old('il') == 'Giresun') selected @endif>Giresun</option>
                <option value="Gümüşhane" @if(old('il') == 'Gümüşhane') selected @endif>Gümüşhane</option>
                <option value="Hakkari" @if(old('il') == 'Hakkari') selected @endif>Hakkari</option>
                <option value="Hatay" @if(old('il') == 'Hatay') selected @endif>Hatay</option>
                <option value="Isparta" @if(old('il') == 'Isparta') selected @endif>Isparta</option>
                <option value="Mersin" @if(old('il') == 'Mersin') selected @endif>Mersin</option>
                <option value="İstanbul" @if(old('il') == 'İstanbul') selected @endif>İstanbul</option>
                <option value="İzmir" @if(old('il') == 'İzmir') selected @endif>İzmir</option>
                <option value="Kars" @if(old('il') == 'Kars') selected @endif>Kars</option>
                <option value="Kastamonu" @if(old('il') == 'Kastamonu') selected @endif>Kastamonu</option>
                <option value="Kayseri" @if(old('il') == 'Kayseri') selected @endif>Kayseri</option>
                <option value="Kırklareli" @if(old('il') == 'Kırklareli') selected @endif>Kırklareli</option>
                <option value="Kırşehir" @if(old('il') == 'Kırşehir') selected @endif>Kırşehir</option>
                <option value="Kocaeli" @if(old('il') == 'Kocaeli') selected @endif>Kocaeli</option>
                <option value="Konya" @if(old('il') == 'Konya') selected @endif>Konya</option>
                <option value="Kütahya" @if(old('il') == 'Kütahya') selected @endif>Kütahya</option>
                <option value="Malatya" @if(old('il') == 'Malatya') selected @endif>Malatya</option>
                <option value="Manisa" @if(old('il') == 'Manisa') selected @endif>Manisa</option>
                <option value="Kahramanmaraş" @if(old('il') == 'Kahramanmaraş') selected @endif>Kahramanmaraş</option>
                <option value="Mardin" @if(old('il') == 'Mardin') selected @endif>Mardin</option>
                <option value="Muğla" @if(old('il') == 'Muğla') selected @endif>Muğla</option>
                <option value="Muş" @if(old('il') == 'Muş') selected @endif>Muş</option>
                <option value="Nevşehir" @if(old('il') == 'Nevşehir') selected @endif>Nevşehir</option>
                <option value="Niğde" @if(old('il') == 'Niğde') selected @endif>Niğde</option>
                <option value="Ordu" @if(old('il') == 'Ordu') selected @endif>Ordu</option>
                <option value="Rize" @if(old('il') == 'Rize') selected @endif>Rize</option>
                <option value="Sakarya" @if(old('il') == 'Sakarya') selected @endif>Sakarya</option>
                <option value="Samsun" @if(old('il') == 'Samsun') selected @endif>Samsun</option>
                <option value="Siirt" @if(old('il') == 'Siirt') selected @endif>Siirt</option>
                <option value="Sinop" @if(old('il') == 'Sinop') selected @endif>Sinop</option>
                <option value="Sivas" @if(old('il') == 'Sivas') selected @endif>Sivas</option>
                <option value="Tekirdağ" @if(old('il') == 'Tekirdağ') selected @endif>Tekirdağ</option>
                <option value="Tokat" @if(old('il') == 'Tokat') selected @endif>Tokat</option>
                <option value="Trabzon" @if(old('il') == 'Trabzon') selected @endif>Trabzon</option>
                <option value="Tunceli" @if(old('il') == 'Tunceli') selected @endif>Tunceli</option>
                <option value="Şanlıurfa" @if(old('il') == 'Şanlıurfa') selected @endif>Şanlıurfa</option>
                <option value="Uşak" @if(old('il') == 'Uşak') selected @endif>Uşak</option>
                <option value="Van" @if(old('il') == 'Van') selected @endif>Van</option>
                <option value="Yozgat" @if(old('il') == 'Yozgat') selected @endif>Yozgat</option>
                <option value="Zonguldak" @if(old('il') == 'Zonguldak') selected @endif>Zonguldak</option>
                <option value="Aksaray" @if(old('il') == 'Aksaray') selected @endif>Aksaray</option>
                <option value="Bayburt" @if(old('il') == 'Bayburt') selected @endif>Bayburt</option>
                <option value="Karaman" @if(old('il') == 'Karaman') selected @endif>Karaman</option>
                <option value="Kırıkkale" @if(old('il') == 'Kırıkkale') selected @endif>Kırıkkale</option>
                <option value="Batman" @if(old('il') == 'Batman') selected @endif>Batman</option>
                <option value="Şırnak" @if(old('il') == 'Şırnak') selected @endif>Şırnak</option>
                <option value="Bartın" @if(old('il') == 'Bartın') selected @endif>Bartın</option>
                <option value="Ardahan" @if(old('il') == 'Ardahan') selected @endif>Ardahan</option>
                <option value="Iğdır" @if(old('il') == 'Iğdır') selected @endif>Iğdır</option>
                <option value="Yalova" @if(old('il') == 'Yalova') selected @endif>Yalova</option>
                <option value="Karabük" @if(old('il') == 'Karabük') selected @endif>Karabük</option>
                <option value="Kilis" @if(old('il') == 'Kilis') selected @endif>Kilis</option>
                <option value="Osmaniye" @if(old('il') == 'Osmaniye') selected @endif>Osmaniye</option>
                <option value="Düzce" @if(old('il') == 'Düzce') selected @endif>Düzce</option>
            </select>
        @elseif($field->field_name === 'parabirimi')
            <!-- Para birimi dropdown -->
            <select id="{{ $field->field_name }}" name="{{ $field->field_name }}" 
                    @if($field->is_required) required @endif
                    class="w-full px-4 py-3 bg-zinc-800/50 border border-zinc-700/50 rounded-lg text-white focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-all duration-200">
                <option value="">Para birimi seçiniz</option>
                <option value="₺" @if(old('parabirimi') == '₺') selected @endif>₺ Türk Lirası</option>
                <option value="€" @if(old('parabirimi') == '€') selected @endif>€ Euro</option>
                <option value="$" @if(old('parabirimi') == '$') selected @endif>$ Dolar</option>
            </select>
        @endif
    @else
        <!-- Regular input fields -->
        @php
            $inputType = 'text';
            $placeholder = '';
            $attributes = '';
            
            switch($field->field_type) {
                case 'email':
                    $inputType = 'email';
                    $placeholder = 'ornek@email.com';
                    break;
                case 'tel':
                    $inputType = 'tel';
                    $placeholder = '05XXXXXXXXX';
                    break;
                case 'date':
                    $inputType = 'date';
                    break;
                case 'text':
                default:
                    switch($field->field_name) {
                        case 'firstName':
                            $placeholder = 'Adınızı girin';
                            break;
                        case 'lastName':
                            $placeholder = 'Soyadınızı girin';
                            break;
                        case 'tc':
                            $placeholder = 'T.C. Kimlik numaranız';
                            $attributes = 'maxlength="11" pattern="[0-9]{11}"';
                            break;
                        case 'ilce':
                            $placeholder = 'İlçenizi girin';
                            break;
                        case 'postakodu':
                            $placeholder = '34000';
                            $attributes = 'maxlength="5" pattern="[0-9]{5}"';
                            break;
                        case 'username':
                            $placeholder = 'En az 6 karakter';
                            break;
                        default:
                            $placeholder = $field->field_label . ' girin';
                            break;
                    }
                    break;
            }
        @endphp
        
        <input type="{{ $inputType }}" 
               id="{{ $field->field_name }}" 
               name="{{ $field->field_name }}" 
               value="{{ old($field->field_name) }}" 
               @if($field->is_required) required @endif
               {!! $attributes !!}
               class="w-full px-4 py-3 bg-zinc-800/50 border border-zinc-700/50 rounded-lg text-white placeholder-zinc-500 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-all duration-200" 
               placeholder="{{ $placeholder }}">
    @endif
</div>
@endif