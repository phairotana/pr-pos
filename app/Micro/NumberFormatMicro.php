<?php

namespace App\Micro;

use Illuminate\Support\Str;

class NumberFormatMicro {
    /**
     * @param number|double $value
     * @param number $digit
     * @return string
     */

    protected $m2sub = 'm<sup>2</sup>';

    public function numberFormatDollar() {
        return function($value, $digit = 2, $prefix = '$', $string = '-') {
            if($value == null || $value == ''){
                $value = 0;
            }
            if (is_numeric($value)) {
                return $prefix.number_format($value, $digit);
            }
            return $string;
        };
    }

    public function IdPrefixNumber(){
        return  function($value){
            $arr = [];
            foreach($value as $kry => $vase){
                $arr[] = str_pad($vase, 6, "0", STR_PAD_LEFT);
            }
            return implode(',',$arr);
        };
    }

    public function numberFormatSquare() {
        $m2subs = $this->m2sub;
        return function($value, $prefix = null, $string = '-') use ($m2subs){
            $prefix = $prefix ?? $m2subs;
            if (is_numeric($value)) {
                return number_format($value).$prefix;
            }
            return $string;
        };
    }

    public function numberFormatSquareTwoDigit() {
        $m2subs = $this->m2sub;
        return function($value, $prefix = null, $string = '-') use ($m2subs){
            $prefix = $prefix ?? $m2subs;
            if (is_numeric($value)) {
                $pos = strpos($value, '.');

                if ($pos !== false) {
                    return number_format($value, 2, '.', ',').$prefix;
                }else{
                    return number_format($value, 0, '.', ',').$prefix;
                }
            }
            return $string;
        };
    }

    public function booleanFormatTrueFalse() {
        return function($value) {
            if($value) {
                return $value == 1 ? true : false;
            }
            return false;
        };
    }

    public function numberFomatGeneral() {
        return function($value, $string = ' '){
            if($value){
                return number_format($value);
            }
            return $string;
        };
    }

    public function numberFormatTwoDigitAtLast() {
        return function($value, $digit = 2, $m2 = null){
            if($value){
                $pos = strpos($value, '.');

                if ($pos !== false) {
                    return number_format($value, $digit, '.', ',').$m2; // 1,200.00
                }else{
                    return number_format($value, 0, '.', ',').$m2;      // 1,200
                }
            }
        };
    }

    public function numberFormatTwoDigitAtComma() {
        $m2subs = $this->m2sub;
        return function($value, $digit = 2) use ($m2subs){
            if($value){
                $pos = strpos($value, '.');

                if ($pos !== false) {
                    return number_format($value, $digit, '.', ',').$m2subs; // 1,200.00
                }else{
                    return number_format($value, 0, '.', ',').$m2subs;      // 1,200
                }
            }
        };
    }
    public function numberFormatCommission() {
        return function($value) {
            $pos = strpos($value, '%');
            if ($pos !== false) {
                return ($value);
            } else {
                return Str::numberFormatDollar($value);
            }
        };
    }
    public function stringReplace()
    {
        return function($replacements, $string) {
            if($replacements){
                return str_replace(array_keys($replacements), $replacements, $string);
            }
            return $string;
        };
    }

    public function numberFormatInKhmer(){
        return function($completeChar, $enableThousand = true) {
            //REMOVE LEFT ZERO
            $cleanStr = ltrim($completeChar, '0');

            //SPLIT NUMBER/STRING TO ARRAY
            $numArr = mb_str_split($cleanStr);
            $translated = '';
            $addThousand =false;

            //STRING ARRAY
            $khNUMTxt = array('','មួយ','ពីរ','បី','បួន','ប្រាំ');
            $twoLetter = array('','ដប់','ម្ភៃ','សាមសិប','សែសិប','ហាសិប','ហុកសិប','ចិតសិប','ប៉ែតសិប','កៅសិប');
            $khNUMLev = array('','','','រយ','ពាន់','មឿន','សែន','លាន');
            $khnum = array('០','១','២','៣','៤','៥','៦','៧','៨','៩');

            //LOOP TO CHECK EACH NUMBER CHARACTER
            foreach($numArr as $key=>$value){
                //CONVERT KHMER NUMBER TO LATIN NUMBER IF FOUND
                if(in_array($value,$khnum)){
                    $value = array_search($value,$khnum);
                }

                //ALLOW ONLY NUMBER
                if(!is_numeric($value)){
                    return '';
                }

                //CHECK WHAT POS THE CHARACTOR IN
                $pos = count($numArr) - ($key);
                if($pos > count($khNUMLev) - 1){
                    $pos=($pos % count($khNUMLev))+2;
                }

                //ENABLE OR DIABLE READ IN THOUSAND
                if($enableThousand && ($pos == 5 || $pos == 6)){
                    $pos = $pos-3;
                }

                //CONCATENATE NUMBER AS TEXT
                if($pos == 2){
                    $translated .= $twoLetter[$value];
                }else{
                    if($value>5){
                        $translated .=  $khNUMTxt[5].$khNUMTxt[$value - 5];
                    }else{
                        $translated .= $khNUMTxt[$value];
                    }
                }

                //WORK FOR THOUSAND
                if($pos == 2 || $pos == 3 || $pos == 4){
                    if($value>0){
                        $addThousand=true;
                    }
                }

                //CONCATENATE NUMBER LEVEL
                if($value > 0 || ($pos == 4 && $addThousand && $enableThousand) || $pos == 7){
                    $translated .= $khNUMLev[$pos];
                }

                // MAKE ADD THOUSANS TO DEFAULT VALUE (FALSE)
                if($pos == 4){
                    $addThousand = false;
                }
            }
            //RETURN THE COMPLETE NUMBER IN TEXT

            return $translated;
        };
    }

    public function numberFormatShortAbs() {
        return function ($value, $abs = false, $precision = 1) {
            if ($abs) {
                $value = abs($value);
            }

            if ($value < 900) {
                // 0 - 900
                $valueFormat = number_format($value, $precision);
                $suffix = '';
            } elseif ($value < 900000) {
                // 0.9k-850k
                $valueFormat = number_format($value / 1000, $precision);
                $suffix = 'K';
            } elseif ($value < 900000000) {
                // 0.9m-850m
                $valueFormat = number_format($value / 1000000, $precision);
                $suffix = 'M';
            } elseif ($value < 900000000000) {
                // 0.9b-850b
                $valueFormat = number_format($value / 1000000000, $precision);
                $suffix = 'B';
            } else {
                // 0.9t+
                $valueFormat = number_format($value / 1000000000000, $precision);
                $suffix = 'T';
            }

            // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
            // Intentionally does not affect partials, eg "1.50" -> "1.50"
            if ($precision > 0) {
                $dotzero = '.' . str_repeat('0', $precision);
                $valueFormat = str_replace($dotzero, '', $valueFormat);
            }

            return $valueFormat . $suffix;
        };
    }

    public function numberFormatShortCurrency() {
        return function ($value, $sign = '$', $nullSign = 'N/A') {
            $abs = false;
            $sub = '';
            if ($value < 0) {
                $abs = true;
                $sub = '-';
            }
            return $value ? $sub . $sign . Str::numberFormatShortAbs($value, $abs) : $nullSign;
        };
    }

    public function numberFormatCurrencyDynamicPrefix() {
        return function ($value, $sign = '$', $digit = 2, $front = false, $nullSign = 'N/A') {
            $frontPrefix = '';
            $endPrefix = '';

            if ($front) {
                $frontPrefix = $sign;
            } else {
                $endPrefix = $sign;
            }

            return $value ? $frontPrefix . number_format($value, $digit, '.', ',') . $endPrefix : $nullSign;
        };
    }

    public function numberFormatWithOriginalVal() {
        return function($value, $prefix = null){
            if (!empty($value)) {
                return $value.$prefix;
            }
            return $value;
        };
    }
}
