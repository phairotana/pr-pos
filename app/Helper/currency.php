<?php

use App\Models\Option;

if (!function_exists('convertUSKHamount')) {
    function convertUSKHamount($amount)
    {
        $currency_kh = Option::OptionByType('currency_kh')->first();
        if(!empty($currency_kh)) {
            return ($amount * $currency_kh->value);
        } else {
            return ($amount * 4100);
        }
    }
}
if (!function_exists('convertKhUnit')) {
    function convertKhUnit()
    {
        $currency_kh = Option::OptionByType('currency_kh')->first();
        if(!empty($currency_kh)) {
            return $currency_kh->value;
        } else {
            return 4100;
        }
    }
}
