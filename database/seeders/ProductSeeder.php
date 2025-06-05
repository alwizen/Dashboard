<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            'Pertalite',
            'Pertamax',
            'Bio Solar',
            'Pertamina Dex',
        ];

        foreach ($products as $productName) {
            Product::updateOrCreate(
                ['name' => $productName],
                ['slug' => Str::slug($productName)]
            );
        }
    }
}
