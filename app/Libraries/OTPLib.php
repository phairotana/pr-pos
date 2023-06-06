<?php

namespace App\Libraries;

use Carbon\Carbon;
use App\Models\VerifyOTP;
use GuzzleHttp\Exception\ClientException;

class OTPLib
{
    public static function sendAnySms($phone)
    {
        $code = self::myRandom(4);
        $message = $code . " គឺជាលេខកូដផ្ញើសាររបស់អ្នក \n" . $code . " is your Verification Code ";
        VerifyOTP::updateOrCreate([ // Update or create record by phone
            'phone' => $phone
        ], [
            'code' => $code,
            'message' => $message,
            'expire' => Carbon::now()->addMinutes(5)->toDateTimeString()
        ]);
        return self::mekongNetProvider($phone, $message);
    }
    public static function mekongNetProvider($phone, $message)
    {
        $client = new \GuzzleHttp\Client([
            'allow_redirects' => false,
            'timeout'  => 15,
        ]);
        try {
            $response = $client->post(config('const.OTP.api'), [
                'form_params' => [
                    'username' => config('const.OTP.username'),
                    'pass' => '96e79218965eb72c92a549dd5a330112',
                    'sender' => config('const.OTP.sender'),
                    'smstext' => $message,
                    'gsm' => $phone
                ],
            ]);
            $body = $response->getBody();

            if ($response->getStatusCode() == '200' && $body) {
                $body = json_decode($body);
                \Log::info($body);
                return response($body);
            }
        } catch (ClientException $e) {
            // \Log::error($e);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // \Log::error($e);
        } catch (\Throwable $th) {
            // \Log::error($th);
        }
    }
    public static function reverseToInternationalNumber($phone)
    {
        $isZero = mb_substr($phone, 0, 1) == '0';
        $is855 = mb_substr($phone, 0, 3) == '855';
        if ($isZero) {
            $phone = substr($phone, 1);
            $phone = '+855' . $phone;
        } else if ($is855) {
            $phone = '+' . $phone;
        }
        return $phone;
    }
    private static function myRandom($size = 6)
    {
        $d = '';
        for ($i = 0; $i < $size; $i++) {
            $d .= mt_rand(1, 9);
        }
        return $d;
    }
}
