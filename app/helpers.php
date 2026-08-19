<?php

if (!function_exists('number_format_short')) {
    /**
     * Konversi angka besar menjadi format singkat (misal: 1000 -> 1k, 1200000 -> 1.2M)
     *
     * @param int|float $number
     * @param int $precision
     * @return string
     */
    function number_format_short($number, $precision = 1)
    {
        if ($number < 1000) {
            return number_format($number);
        }

        if ($number < 1000000) {
            $formatted = number_format($number / 1000, $precision);
            return rtrim(rtrim($formatted, '0'), '.') . 'k';
        }

        if ($number < 1000000000) {
            $formatted = number_format($number / 1000000, $precision);
            return rtrim(rtrim($formatted, '0'), '.') . 'M';
        }

        $formatted = number_format($number / 1000000000, $precision);
        return rtrim(rtrim($formatted, '0'), '.') . 'B';
    }
}
