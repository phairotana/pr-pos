<?php

return [
    'formatDate' => [
        'date_number' => 'd/m/Y',
        'date_string' => 'j-F-Y',
        'datetime' => 'd-m-Y H:i'
    ],
    'filePath' => [
        'small' => env('DO_SPACE_NAME') . '/uploads/files/small/',
        'medium' => env('DO_SPACE_NAME') . '/uploads/files/medium/',
        'large' => env('DO_SPACE_NAME') . '/uploads/files/large/',
        'original' => env('DO_SPACE_NAME') . '/uploads/files/original/',
        'default_image' => 'uploads/default/default_image.png',
        'default' => 'uploads/default/default_image.png',
    ],
    'currency_symbol' => [
        'dollar' => '$'
    ],
    'OTP' => [
        'username' => 'ngov_hong_sms@mekongnet',
        'password' => '96e79218965eb72c92a549dd5a330112',
        'sender' => 'Mr Hang',
        'api' => 'https://api.mekongsms.com/api/postsms.aspx'
    ],
    'firebase' => [
        'url' => 'https://fcm.googleapis.com/fcm/send',
        'server_api_key' => 'AAAAhUy58BA:APA91bGRKkKR2K5UvA3T-DAHLZbk4iyhS5TBIytwpRlfWgMR15fYhen7EpQ52Y6VV71wfZ7JC6w0btyFI3x2N6PtplPgzwwaW8irA1gaHYaF8jt_GH6LSgq1r9W4w8DU3RoAKd4D-fV8'
    ],
];
