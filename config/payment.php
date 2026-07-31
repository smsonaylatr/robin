<?php

return [
    'methods' => [
        'HEMEN_HAVALE' => [
            'apiurl' => 'https://api.hemenode.biz/trader/get-deposit-url',
            'method_id' => '633416cf4f3595f9463f5589',
            'api_key' => function() { return \DB::table('payment_settings')->where('setting_key', 'hemen_havale_api_key')->value('setting_value') ?? '6777a612e26a6c803c7a9add'; }
        ],
        'MEFETE' => [
            'apiurl' => 'https://api.vipmefete.com/trader/get-deposit-url',
            'method_id' => '633416ad4f3595f9463f5585',
            'api_key' => function() { return \DB::table('payment_settings')->where('setting_key', 'mefete_api_key')->value('setting_value') ?? '6777a612e26a6c803c7a9ae0'; }
        ],
        'PAPARA' => [
            'apiurl' => 'https://api.hmnpay.com/trader/get-deposit-url',
            'method_id' => '633417394f3595f9463f558f',
            'api_key' => function() { return \DB::table('payment_settings')->where('setting_key', 'papara_api_key')->value('setting_value') ?? '6777a612e26a6c803c7a9ae6'; }
        ],
        'HEMEN_PAROLAPARA' => [
            'apiurl' => 'https://api1.vipparola.com/trader/get-deposit-url',
            'method_id' => '63763a78c01cfa7964c61ad7',
            'api_key' => function() { return \DB::table('payment_settings')->where('setting_key', 'hemen_parolapara_api_key')->value('setting_value') ?? '6777a612e26a6c803c7a9af2'; }
        ],
        'KREDI_KARTI' => [
            'apiurl' => 'https://api.vip3d.net/trader/get-deposit-url',
            'method_id' => '633416cf4f3595f9463f5589',
            'api_key' => function() { return \DB::table('payment_settings')->where('setting_key', 'kredi_karti_api_key')->value('setting_value') ?? '66d7fc57f271aceccbf75e2a'; }
        ],
        'HEMEN_KRIPTO' => [
            'apiurl' => 'https://api.hmnkripto.com/trader/get-deposit-url',
            'method_id' => '633417664f3595f9463f5593',
            'api_key' => function() { return \DB::table('payment_settings')->where('setting_key', 'hemen_kripto_api_key')->value('setting_value') ?? '67516c5e343e4e740556c060'; }
        ],
        // Extra Cüzdan Ödeme Yöntemleri
        'EXTRA_HAVALE_EFT' => [
            'apiurl' => 'https://apiws.extracuzdan.com/deposit/havaleeft',
            'method_id' => 'havaleeft',
            'api_key' => function() { return \DB::table('payment_settings')->where('setting_key', 'extra_api_key')->value('setting_value') ?? 'apikey-08f43403-a238-4eeb-b1b6-a9dd379a1c70'; },
            'secret' => function() { return \DB::table('payment_settings')->where('setting_key', 'extra_secret')->value('setting_value') ?? 'c92539a2-4e6f-49c9-b7f3-6adbc3615695'; },
            'provider' => 'extra'
        ],
        'EXTRA_HAVALE_FAST' => [
            'apiurl' => 'https://apiws.extracuzdan.com/deposit/havalefast',
            'method_id' => 'havalefast',
            'api_key' => function() { return \DB::table('payment_settings')->where('setting_key', 'extra_api_key')->value('setting_value') ?? 'apikey-08f43403-a238-4eeb-b1b6-a9dd379a1c70'; },
            'secret' => function() { return \DB::table('payment_settings')->where('setting_key', 'extra_secret')->value('setting_value') ?? 'c92539a2-4e6f-49c9-b7f3-6adbc3615695'; },
            'provider' => 'extra'
        ],
        'EXTRA_PAPARA' => [
            'apiurl' => 'https://apiws.extracuzdan.com/deposit/papara',
            'method_id' => 'papara',
            'api_key' => function() { return \DB::table('payment_settings')->where('setting_key', 'extra_api_key')->value('setting_value') ?? 'apikey-08f43403-a238-4eeb-b1b6-a9dd379a1c70'; },
            'secret' => function() { return \DB::table('payment_settings')->where('setting_key', 'extra_secret')->value('setting_value') ?? 'c92539a2-4e6f-49c9-b7f3-6adbc3615695'; },
            'provider' => 'extra'
        ],
        'EXTRA_PAPARA_IBAN' => [
            'apiurl' => 'https://apiws.extracuzdan.com/deposit/paparaiban',
            'method_id' => 'paparaiban',
            'api_key' => function() { return \DB::table('payment_settings')->where('setting_key', 'extra_api_key')->value('setting_value') ?? 'apikey-08f43403-a238-4eeb-b1b6-a9dd379a1c70'; },
            'secret' => function() { return \DB::table('payment_settings')->where('setting_key', 'extra_secret')->value('setting_value') ?? 'c92539a2-4e6f-49c9-b7f3-6adbc3615695'; },
            'provider' => 'extra'
        ],
        'EXTRA_KREDI_KARTI' => [
            'apiurl' => 'https://apiws.extracuzdan.com/deposit/creditcard',
            'method_id' => 'creditcard',
            'api_key' => function() { return \DB::table('payment_settings')->where('setting_key', 'extra_api_key')->value('setting_value') ?? 'apikey-08f43403-a238-4eeb-b1b6-a9dd379a1c70'; },
            'secret' => function() { return \DB::table('payment_settings')->where('setting_key', 'extra_secret')->value('setting_value') ?? 'c92539a2-4e6f-49c9-b7f3-6adbc3615695'; },
            'provider' => 'extra'
        ],
        'EXTRA_MEFETE' => [
            'apiurl' => 'https://apiws.extracuzdan.com/deposit/mefete',
            'method_id' => 'mefete',
            'api_key' => function() { return \DB::table('payment_settings')->where('setting_key', 'extra_api_key')->value('setting_value') ?? 'apikey-08f43403-a238-4eeb-b1b6-a9dd379a1c70'; },
            'secret' => function() { return \DB::table('payment_settings')->where('setting_key', 'extra_secret')->value('setting_value') ?? 'c92539a2-4e6f-49c9-b7f3-6adbc3615695'; },
            'provider' => 'extra'
        ],
        'EXTRA_KRIPTO' => [
            'apiurl' => 'https://apiws.extracuzdan.com/deposit/kripto',
            'method_id' => 'kripto',
            'api_key' => function() { return \DB::table('payment_settings')->where('setting_key', 'extra_api_key')->value('setting_value') ?? 'apikey-08f43403-a238-4eeb-b1b6-a9dd379a1c70'; },
            'secret' => function() { return \DB::table('payment_settings')->where('setting_key', 'extra_secret')->value('setting_value') ?? 'c92539a2-4e6f-49c9-b7f3-6adbc3615695'; },
            'provider' => 'extra'
        ],
        'EXTRA_KASSA' => [
            'apiurl' => 'https://apiws.extracuzdan.com/deposit/kassa',
            'method_id' => 'kassa',
            'api_key' => function() { return \DB::table('payment_settings')->where('setting_key', 'extra_api_key')->value('setting_value') ?? 'apikey-08f43403-a238-4eeb-b1b6-a9dd379a1c70'; },
            'secret' => function() { return \DB::table('payment_settings')->where('setting_key', 'extra_secret')->value('setting_value') ?? 'c92539a2-4e6f-49c9-b7f3-6adbc3615695'; },
            'provider' => 'extra'
        ],
        'EXTRA_PAPEL' => [
            'apiurl' => 'https://apiws.extracuzdan.com/deposit/papel',
            'method_id' => 'papel',
            'api_key' => function() { return \DB::table('payment_settings')->where('setting_key', 'extra_api_key')->value('setting_value') ?? 'apikey-08f43403-a238-4eeb-b1b6-a9dd379a1c70'; },
            'secret' => function() { return \DB::table('payment_settings')->where('setting_key', 'extra_secret')->value('setting_value') ?? 'c92539a2-4e6f-49c9-b7f3-6adbc3615695'; },
            'provider' => 'extra'
        ],
        'EXTRA_PAYCO' => [
            'apiurl' => 'https://apiws.extracuzdan.com/deposit/payco',
            'method_id' => 'payco',
            'api_key' => function() { return \DB::table('payment_settings')->where('setting_key', 'extra_api_key')->value('setting_value') ?? 'apikey-08f43403-a238-4eeb-b1b6-a9dd379a1c70'; },
            'secret' => function() { return \DB::table('payment_settings')->where('setting_key', 'extra_secret')->value('setting_value') ?? 'c92539a2-4e6f-49c9-b7f3-6adbc3615695'; },
            'provider' => 'extra'
        ],
        'EXTRA_PARAZULA' => [
            'apiurl' => 'https://apiws.extracuzdan.com/deposit/parazula',
            'method_id' => 'parazula',
            'api_key' => function() { return \DB::table('payment_settings')->where('setting_key', 'extra_api_key')->value('setting_value') ?? 'apikey-08f43403-a238-4eeb-b1b6-a9dd379a1c70'; },
            'secret' => function() { return \DB::table('payment_settings')->where('setting_key', 'extra_secret')->value('setting_value') ?? 'c92539a2-4e6f-49c9-b7f3-6adbc3615695'; },
            'provider' => 'extra'
        ],
        /* 'OLEY_CREDIT_CARD' => [
            'apiurl' => 'https://api.oleypayment.com/iframe/creditcard/newtransaction',
            'method_id' => 'creditcard',
            'api_key' => function() { return \DB::table('payment_settings')->where('setting_key', 'oley_api_key')->value('setting_value') ?? ''; },
            'secret' => function() { return \DB::table('payment_settings')->where('setting_key', 'oley_secret')->value('setting_value') ?? ''; },
            'provider' => 'oleypayment',
            'type' => 'iframe'
        ],
        'OLEY_PAPARA' => [
            'apiurl' => 'https://api.oleypayment.com/iframe/papara/newtransaction',
            'method_id' => 'papara',
            'api_key' => function() { return \DB::table('payment_settings')->where('setting_key', 'oley_api_key')->value('setting_value') ?? ''; },
            'secret' => function() { return \DB::table('payment_settings')->where('setting_key', 'oley_secret')->value('setting_value') ?? ''; },
            'provider' => 'oleypayment',
            'type' => 'iframe'
        ], */
        /* 'OLEY_MEFETE' => [
            'apiurl' => 'https://api.oleypayment.com/iframe/mefete/newtransaction',
            'method_id' => 'mefete',
            'api_key' => function() { return \DB::table('payment_settings')->where('setting_key', 'oley_api_key')->value('setting_value') ?? ''; },
            'secret' => function() { return \DB::table('payment_settings')->where('setting_key', 'oley_secret')->value('setting_value') ?? ''; },
            'provider' => 'oleypayment',
            'type' => 'iframe'
        ],
        'PARAZULA' => [
            'apiurl' => 'https://api.oleypayment.com/iframe/parazula/newtransaction',
            'method_id' => 'parazula',
            'api_key' => function() { return \DB::table('payment_settings')->where('setting_key', 'oley_api_key')->value('setting_value') ?? ''; },
            'secret' => function() { return \DB::table('payment_settings')->where('setting_key', 'oley_secret')->value('setting_value') ?? ''; },
            'provider' => 'oleypayment',
            'type' => 'iframe'
        ], */
        /* 'POPY' => [
            'apiurl' => 'https://api.oleypayment.com/iframe/popy/newtransaction',
            'method_id' => 'popy',
            'api_key' => function() { return \DB::table('payment_settings')->where('setting_key', 'oley_api_key')->value('setting_value') ?? ''; },
            'secret' => function() { return \DB::table('payment_settings')->where('setting_key', 'oley_secret')->value('setting_value') ?? ''; },
            'provider' => 'oleypayment',
            'type' => 'iframe'
        ],
        'PAYCO' => [
            'apiurl' => 'https://api.oleypayment.com/iframe/payco/newtransaction',
            'method_id' => 'payco',
            'api_key' => function() { return \DB::table('payment_settings')->where('setting_key', 'oley_api_key')->value('setting_value') ?? ''; },
            'secret' => function() { return \DB::table('payment_settings')->where('setting_key', 'oley_secret')->value('setting_value') ?? ''; },
            'provider' => 'oleypayment',
            'type' => 'iframe'
        ], */
        /* 'PAYFIX' => [
            'apiurl' => 'https://api.oleypayment.com/iframe/payfix/newtransaction',
            'method_id' => 'payfix',
            'api_key' => function() { return \DB::table('payment_settings')->where('setting_key', 'oley_api_key')->value('setting_value') ?? ''; },
            'secret' => function() { return \DB::table('payment_settings')->where('setting_key', 'oley_secret')->value('setting_value') ?? ''; },
            'provider' => 'oleypayment',
            'type' => 'iframe'
        ] */
    ]
];
