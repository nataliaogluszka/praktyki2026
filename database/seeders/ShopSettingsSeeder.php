<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ShippingMethod;

class ShopSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ShippingMethod::create(['name' => 'Paczkomat InPost', 'slug' => 'paczkomat', 'price' => 15.00]);
        ShippingMethod::create(['name' => 'Kurier InPost', 'slug' => 'kurier_inpost', 'price' => 19.00]);
        ShippingMethod::create(['name' => 'Kurier DHL', 'slug' => 'dhl', 'price' => 22.00]);

        Setting::create(['key' => 'vat_rate', 'value' => '23']);
    }
}
