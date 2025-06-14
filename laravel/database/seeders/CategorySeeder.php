<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'code' => 'BP',
                'name' => 'Bunga Potong',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'BA',
                'name' => 'Bunga Artificial',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'BQ',
                'name' => 'Bouquet',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'D',
                'name' => 'Daun',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        Category::insert($categories);
    }
}
