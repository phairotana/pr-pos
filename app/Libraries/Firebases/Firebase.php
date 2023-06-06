<?php

namespace App\Libraries\Firebases;

use Illuminate\Support\Str;
use App\Models\Notification;
use App\Libraries\Firebases\FirebaseData;

class Firebase
{
    static function sendOrder($user, $orderID, $status)
    {
        $data = FirebaseData::orderData($orderID, $status);
        self::createNotification($user, $data);
    }
    static function createNotification($user, $data)
    {
        $notification = Notification::create([
            'type' => static::class,
            'notifiable_id' => $user->id,
            'notifiable_type' => 'App\User',
            'data' => ['data' => $data['data']]
        ]);
        $data['id'] = $notification->id;
        $data['registration_ids'] = $user->deviceToken->pluck('device_token')->toArray();
        self::pushToFirebase($data);
    }
    static function pushToFirebase($data)
    {
        $headers = [
            'Authorization:key=' . config('const.firebase.server_api_key'),
            'Content-Type: application/json',
        ];
        // payload data, it will vary according to requirement
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, config('const.firebase.url'));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        // Execute post
        $response = curl_exec($ch);
        if ($response === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);
        // FCM response
        // return $response;
    }
}
