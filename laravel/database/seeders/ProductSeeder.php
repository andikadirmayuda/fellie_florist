<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductPrice;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {        // Get categories
        $bungaPotong = Category::where('code', 'BP')->first();
        $bouquet = Category::where('code', 'BQ')->first();

        if (!$bungaPotong || !$bouquet) {
            throw new \Exception('Categories not found. Please ensure CategorySeeder has been run first.');
        }

        // Create Mawar Merah
        $mawarMerah = Product::create([
            'category_id' => $bungaPotong->id,
            'code' => 'MAWAR-MERAH',
            'name' => 'Mawar Merah',
            'description' => 'Bunga mawar merah segar',
            'base_unit' => 'tangkai',
            'current_stock' => 100,
            'min_stock' => 20,
            'is_active' => true,
        ]);

        // Harga untuk Mawar Merah
        $mawarMerah->prices()->createMany([
            [
                'type' => 'per_tangkai',
                'price' => 15000,
                'unit_equivalent' => 1,
                'is_default' => true,
            ],
            [
                'type' => 'ikat_10',
                'price' => 130000,
                'unit_equivalent' => 10,
                'is_default' => false,
            ],
            [
                'type' => 'ikat_20',
                'price' => 250000,
                'unit_equivalent' => 20,
                'is_default' => false,
            ],
        ]);

        // Create Bunga Matahari
        $bungaMatahari = Product::create([
            'category_id' => $bungaPotong->id,
            'code' => 'SUNFLOWER',
            'name' => 'Bunga Matahari',
            'description' => 'Bunga matahari segar',
            'base_unit' => 'tangkai',
            'current_stock' => 50,
            'min_stock' => 10,
            'is_active' => true,
        ]);

        // Harga untuk Bunga Matahari
        $bungaMatahari->prices()->createMany([
            [
                'type' => 'per_tangkai',
                'price' => 25000,
                'unit_equivalent' => 1,
                'is_default' => true,
            ],
            [
                'type' => 'ikat_5',
                'price' => 110000,
                'unit_equivalent' => 5,
                'is_default' => false,
            ],
            [
                'type' => 'ikat_10',
                'price' => 200000,
                'unit_equivalent' => 10,
                'is_default' => false,
            ],
        ]);

        // Create Bouquet Romantis
        $bouquetRomantis = Product::create([
            'category_id' => $bouquet->id,
            'code' => 'BOUQUET-ROMANTIS',
            'name' => 'Bouquet Romantis',
            'description' => 'Bouquet bunga mawar mix dengan baby breath',
            'base_unit' => 'item',
            'current_stock' => 5,
            'min_stock' => 3,
            'is_active' => true,
        ]);

        // Harga untuk Bouquet Romantis
        $bouquetRomantis->prices()->createMany([
            [
                'type' => 'normal',
                'price' => 350000,
                'unit_equivalent' => 1,
                'is_default' => true,
            ],
            [
                'type' => 'reseller',
                'price' => 300000,
                'unit_equivalent' => 1,
                'is_default' => false,
            ],
            [
                'type' => 'promo',
                'price' => 325000,
                'unit_equivalent' => 1,
                'is_default' => false,
            ],
        ]);
    }
}
