<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Models\User;

class Helper
{
    public static function randomColor()
    {
        return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
    }

    /**
     * @return array
     */
    public static function thisMonthBetween()
    {
        $start = new Carbon('first day of this month');
        $end = new Carbon('last day of this month');   
        return [$start, $end];
    }

    /**
     * @param string $value
     * @return boolean
     */
    public static function isUrl($value)
    {
        if (!empty($value) && filter_var($value, FILTER_VALIDATE_URL) !== false) {
            return true;
        }
        return false;
    }
    /**
     * @param int $value
     * @param int $length
     * @param string $symbol
     * @return string
     * USAGE: 
     * Helper::formatCurrency(120.50)
     */
    static function formatCurrency($value, $symbol = null, $showSymbol = true, $length = 2)
    {
        if (is_numeric($value) && !empty($symbol) && is_numeric($length)) {
            if (strtolower($symbol) == "usd" || $symbol == "$") {
                return self::formatCurrencyDollar($value, $length, $symbol, $showSymbol);
            }
            if (strtolower($symbol) == "riel" || $symbol == "៛") {
                return self::formatCurrencyRiel($value, $symbol, $showSymbol);
            }
        }
        return null;
    }
    /**
     * @param int $value
     * @param int $leng
     * @param string $symbol
     * @return string
     * USAGE: 
     * Helper::formatCurrencyDollar(120.50)
     */
    static function formatCurrencyDollar($value, $leng = 2, $symbol = "$", $showSymbol = true)
    {
        if (is_numeric($value) && is_numeric($leng)) {
            $symbol = strtolower($symbol) == 'usd' ? strtoupper($symbol) : $symbol;
            if ($showSymbol) {
                return $symbol . number_format($value, $leng);
            } else {
                return number_format($value, $leng);
            }
        }
        return null;
    }

    /**
     * @param int $value
     * @param int $leng
     * @param string $symbol
     * @return string
     * USAGE: 
     * Helper::formatCurrencyRiel(120.50)
     */
    static function formatCurrencyRiel($value, $symbol = '៛', $showSymbol = true)
    {
        if (is_numeric($value)) {
            $amount = round($value / 100) * 100;
            $symbol = strtolower($symbol) == 'riel' ? strtoupper($symbol) : $symbol;
            if ($showSymbol) {
                return number_format($amount, 0, ',', ',') . $symbol;
            } else {
                return number_format($amount, 0, ',', ',');
            }
        }
        return null;
    }

    /**
     * @param int $value Number
     * @return string
     */
    static function calculatNumberToK($value)
    {
        $finalReturn = '';
        if (is_numeric($value)) :
            // IF NUMBER BIGER THAN ZERO 
            if ($value >= 1000000000) {
                $val = ($value / 1000000000);
                $finalReturn = round($val, 2) . 'B USD';
            } elseif ($value >= 1000000) {
                $val = ($value / 1000000);
                $finalReturn = round($val, 2) . 'M USD';
            } elseif ($value >= 1000) {
                $val = ($value / 1000);
                $finalReturn = round($val, 2) . 'K USD';
            } elseif ($value >= 0) {
                $finalReturn = round($value, 2) . ' USD';
            } elseif ($value < 0) {
                // IF NUMBER SMALLER THAN ZERO 
                if (($value * (-1)) >= 10000000) {
                    $val = (($value * (-1)) / 10000000);
                    $finalReturn = '-' . round($val, 2) . 'B';
                } elseif (($value * (-1)) >= 1000000) {
                    $val = (($value * (-1)) / 1000000);
                    $finalReturn = '-' . round($val, 2) . 'M';
                } elseif (($value * (-1)) >= 1000) {
                    $val = (($value * (-1)) / 1000);
                    $finalReturn = '-' . round($val, 2) . 'K';
                } else {
                    $finalReturn = round($value, 2);
                }
            }
        endif;
        return $finalReturn;
    }

    // Get User Branch
    public static function userBranch($userId) {
        $user = User::find($userId);
        return $user ? $user->branch_id : null;
    }
}
