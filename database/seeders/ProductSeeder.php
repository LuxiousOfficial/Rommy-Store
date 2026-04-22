<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::factory()->count(10)->create()->each(function ($product) {
            $imageCount = rand(1, 5);

            // First image akan menjadi thumbnail
            ProductImage::factory()->thumbnail()->create([
                'product_id' => $product->id
            ]);

            // Akan menambahkan image jika dibutuhkan
            if($imageCount > 1) {
                ProductImage::factory()->count($imageCount - 1)->create([
                    'product_id' => $product->id
                ]);
            }
        });
    }
}
