<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */    public function run(): void
    {
        $categories = [
            [
                'code' => 'BP',
                'name' => 'Bunga Potong',
                'prefix' => 'BP',
                'next_number' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'BA',
                'name' => 'Bunga Artificial',
                'prefix' => 'BA',
                'next_number' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'BQ',
                'name' => 'Bouquet',
                'prefix' => 'BQ',
                'next_number' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],            [
                'code' => 'D',
                'name' => 'Daun',
                'prefix' => 'D',
                'next_number' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        Category::insert($categories);
    }
}
