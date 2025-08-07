<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductPrice;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get Fresh Flowers category
        $freshFlowersCategory = Category::where('code', 'FF')->first();

        if (!$freshFlowersCategory) {
            $this->command->error('Fresh Flowers category not found. Please run CategorySeeder first.');
            return;
        }

        $products = [
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => 'FF001',
                    'name' => 'Mawar Merah',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 15000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_20',
                        'price' => 150000.00,
                        'unit_equivalent' => 20,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 99500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '1325282930',
                    'name' => 'Mawar Ungu',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 15000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_20',
                        'price' => 150000.00,
                        'unit_equivalent' => 20,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 99500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '938595803',
                    'name' => 'Mawar Biru',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 15000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_20',
                        'price' => 150000.00,
                        'unit_equivalent' => 20,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 99500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '34543535232',
                    'name' => 'Mawar Putih',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 13000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_20',
                        'price' => 130000.00,
                        'unit_equivalent' => 20,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 85000.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '0494049999990',
                    'name' => 'Mawar Pink',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 13000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_20',
                        'price' => 130000.00,
                        'unit_equivalent' => 20,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 85000.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '34543535232',
                    'name' => 'Mawar candy',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 13000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_20',
                        'price' => 130000.00,
                        'unit_equivalent' => 20,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 85000.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '8953958598',
                    'name' => 'Mawar Peach',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 13000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_20',
                        'price' => 130000.00,
                        'unit_equivalent' => 20,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 85000.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '35353532',
                    'name' => 'Mawar Tabur Merah',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'item',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'normal',
                        'price' => 9500.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '2424443434',
                    'name' => 'Mawar Tabur Mix',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'item',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'normal',
                        'price' => 7500.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '8953958598',
                    'name' => 'Mawar Peach',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 13000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_20',
                        'price' => 130000.00,
                        'unit_equivalent' => 20,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 85000.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '78739879993',
                    'name' => 'Aster Putih Jimlah',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 34500.00,
                        'unit_equivalent' => 20,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 30500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '3535235235',
                    'name' => 'Aster Merah Jimlah',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 34500.00,
                        'unit_equivalent' => 20,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 30500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '94343532535',
                    'name' => 'Aster Ungu Jimlah',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 34500.00,
                        'unit_equivalent' => 20,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 30500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '94343532535',
                    'name' => 'Aster Kuning Jimlah',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 34500.00,
                        'unit_equivalent' => 20,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 30500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '94343532535',
                    'name' => 'Aster Orage Jimlah',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 34500.00,
                        'unit_equivalent' => 20,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 30500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '87557857876',
                    'name' => 'Aster Putih Rados',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 34500.00,
                        'unit_equivalent' => 20,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 30500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
        ];

        foreach ($products as $item) {
            $productData = $item['product_data'];
            $pricesData = $item['prices'];

            // Check if product already exists
            $existingProduct = Product::where('code', $productData['code'])->first();

            if (!$existingProduct) {
                // Create product
                $product = Product::create($productData);
                $this->command->info("Product '{$productData['name']}' created successfully.");

                // Create product prices
                foreach ($pricesData as $priceData) {
                    $priceData['product_id'] = $product->id;
                    ProductPrice::create($priceData);
                }
                $this->command->info("Product prices for '{$productData['name']}' created successfully.");
            } else {
                $this->command->info("Product '{$productData['name']}' already exists.");
            }
        }
    }
}
