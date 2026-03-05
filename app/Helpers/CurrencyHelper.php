<?php

namespace App\Helpers;

use App\Models\Setting;

class CurrencyHelper
{
    public static function convert($amountInPln)
    {
        $targetCurrency = Setting::where('key', 'shop_currency')->first()->value ?? 'PLN';
        
        if ($targetCurrency === 'PLN') {
            return number_format($amountInPln, 2, ',', ' ') . ' zł';
        }

        $ratesSetting = Setting::where('key', 'exchange_rates')->first();
        if (!$ratesSetting) return number_format($amountInPln, 2, ',', ' ') . ' PLN (brak kursów)';

        $rates = json_decode($ratesSetting->value, true);
        $rate = $rates[$targetCurrency] ?? 1;

        $converted = $amountInPln * $rate;

        $symbols = ['EUR' => '€', 'USD' => '$', 'GBP' => '£'];
        $symbol = $symbols[$targetCurrency] ?? $targetCurrency;

        return number_format($converted, 2, '.', ' ') . ' ' . $symbol;
    }

    public static function calculateVat($price, $vatRate)
    {
        $vatAmountInPln = $price * ($vatRate / 100);
        return self::convert($vatAmountInPln);
    }
}