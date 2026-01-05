<?php
return [
    'enabled' => true,
    'mode' => 'sandbox',
    'default_share' => false,
    'timeout' => 8,
    'max_retries' => 2,
    'facilities' => [
        [
            'id' => 'rsup_sardjito',
            'name' => 'RSUP Dr. Sardjito Yogyakarta',
            'type' => 'Rumah Sakit Pemerintah Tipe A',
            'location' => 'Yogyakarta',
            'contact_email' => 'integrasi@sardjito.co.id',
            'contact_phone' => '+62-274-587333',
            'endpoint' => getenv('RSUP_SARDJITO_ENDPOINT') ?: 'https://api.sardjito.co.id/v1/referral/mental-health',
            'auth' => [
                'type' => 'token',
                'token' => getenv('RSUP_SARDJITO_TOKEN') ?: 'demo-token-sardjito'
            ],
            'supports_realtime' => true,
            'active' => true
        ],
        [
            'id' => 'rs_jih',
            'name' => 'RS JIH (Jogja International Hospital)',
            'type' => 'Rumah Sakit Swasta',
            'location' => 'Yogyakarta',
            'contact_email' => 'api@jih.co.id',
            'contact_phone' => '+62-274-4463535',
            'endpoint' => getenv('RS_JIH_ENDPOINT') ?: 'https://api.jih.co.id/mental-health/referral',
            'auth' => [
                'type' => 'token',
                'token' => getenv('RS_JIH_TOKEN') ?: 'demo-token-jih'
            ],
            'supports_realtime' => true,
            'active' => true
        ],
        [
            'id' => 'rs_bethesda',
            'name' => 'RS Bethesda Yogyakarta',
            'type' => 'Rumah Sakit Swasta',
            'location' => 'Yogyakarta',
            'contact_email' => 'integrasi@bethesda.co.id',
            'contact_phone' => '+62-274-587799',
            'endpoint' => getenv('RS_BETHESDA_ENDPOINT') ?: 'https://api.bethesda.co.id/api/v1/mental-health-intake',
            'auth' => [
                'type' => 'basic',
                'username' => getenv('RS_BETHESDA_USER') ?: 'sandbox',
                'password' => getenv('RS_BETHESDA_PASS') ?: 'sandbox123'
            ],
            'supports_realtime' => false,
            'active' => true
        ],
        [
            'id' => 'rs_panti_rapih',
            'name' => 'RS Panti Rapih Yogyakarta',
            'type' => 'Rumah Sakit Swasta',
            'location' => 'Yogyakarta',
            'contact_email' => 'api@pantirapih.or.id',
            'contact_phone' => '+62-274-514014',
            'endpoint' => getenv('RS_PANTIRAPIH_ENDPOINT') ?: 'https://api.pantirapih.or.id/referral/mental-health',
            'auth' => [
                'type' => 'token',
                'token' => getenv('RS_PANTIRAPIH_TOKEN') ?: 'demo-token-pantirapih'
            ],
            'supports_realtime' => true,
            'active' => true
        ]
    ]
];
