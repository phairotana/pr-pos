<?php

namespace App\Libraries\Firebases;

class FirebaseData
{
    static function orderData($orderID, $status)
    {
        $body = '';
        if (strtolower($status) == "approved") {
            $body = "Your order #".$orderID." has been approved.";
        } 
        if (strtolower($status) == "delivered") {
            $body = "Your order #".$orderID." is delivered.";
        }
        if (strtolower($status) == "completed") {
            $body = "Your order #".$orderID." has been completed.";
        }
        $data = [
            'title'           => $body,
            'web_message'     => $body,
            'message'         => strip_tags($body),
            'body'            => strip_tags($body),
            'notification_id' => ''
        ];
        return [
            'priority' => 'high',
            'notification' => $data,
            'data' => $data
        ];
    }
}
