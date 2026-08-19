<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CoinPackage;

class CoinPackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'name'           => 'Paket Pemula',
                'beans'          => 50,
                'bonus_beans'    => 0,
                'price'          => 15000,
                'discount_price' => null,
                'badge_label'    => null,
                'order_priority' => 1,
            ],
            [
                'name'           => 'Paket Hemat',
                'beans'          => 100,
                'bonus_beans'    => 5, // Contoh bonus koin
                'price'          => 29000,
                'discount_price' => null,
                'badge_label'    => 'HEMAT',
                'order_priority' => 2,
            ],
            [
                'name'           => 'Paket Populer',
                'beans'          => 250,
                'bonus_beans'    => 20,
                'price'          => 69000,
                'discount_price' => null,
                'badge_label'    => 'POPULER',
                'order_priority' => 3,
            ],
            [
                'name'           => 'Paket Sultan',
                'beans'          => 500,
                'bonus_beans'    => 50,
                'price'          => 129000,
                'discount_price' => 119000, // Contoh diskon
                'badge_label'    => 'BEST VALUE',
                'order_priority' => 4,
            ],
        ];

        foreach ($packages as $pkg) {
            CoinPackage::create($pkg);
        }
    }
}
