<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set($key, $value)
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    public function calculateWithdrawal(int $kisaAmount)
    {
        $rate = (float) Setting::get('kisa_to_rupiah_rate', 150);
        $adminFee = (float) Setting::get('withdrawal_admin_fee', 3000);

        $grossRupiah = $kisaAmount * $rate;
        $netRupiah = max(0, $grossRupiah - $adminFee);

        return [
            'kisa' => $kisaAmount,
            'rate' => $rate,
            'gross_rupiah' => $grossRupiah,
            'admin_fee' => $adminFee,
            'net_rupiah' => $netRupiah,
        ];
    }
}
