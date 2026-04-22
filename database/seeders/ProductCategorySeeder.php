<?php

namespace Database\Seeders;

use App\Helpers\ImageHelper\ImageHelper;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Elektronik',
                'tagline' => 'Temukan berbagai produk elektronik terbaik',
                'description' => 'Kategori produk seperti smartphone, laptop, dan gadget lainnya',
                'children' => [
                    [
                        'name' => 'Smartphone',
                        'tagline' => 'Smartphone terbaru dengan teknologi canggih',
                        'description' => 'Berbagai merek smartphone terbaru dengan spesifikasi tinggi'
                    ],
                    [
                        'name' => 'Laptop',
                        'tagline' => 'Laptop untuk produktivitas maksimal',
                        'description' => 'Koleksi Laptop untuk gaming, kerja dan kebutuhan sehari-hari'
                    ],
                    [
                        'name' => 'Aksesoris Gadget',
                        'tagline' => 'Lengkapi gadget anda dengan aksesoris terbaik',
                        'description' => 'Berbagai aksesoris untuk smartpone dan laptop'
                    ]
                ]
            ],
            [
                'name' => 'Fashion',
                'tagline' => 'Temukan berbagai produk fashion terbaik',
                'description' => 'Kategori produk fashion cantik lainnya',
                'children' => [
                    [
                        'name' => 'Pakaian Pria',
                        'tagline' => 'Koleksi pakaian pria',
                        'description' => 'Berbagai merek pakaian terbaru'
                    ],
                    [
                        'name' => 'Pakaian wanita',
                        'tagline' => 'Koleksi pakaian wanita',
                        'description' => 'Berbagai merek pakaian terbaru'
                    ]
                ]
            ],
            [
                'name' => 'Kesehatan & Kecantikan',
                'tagline' => 'Temukan berbagai produk kesehatan dan kecantikan terbaik',
                'description' => 'Kategori produk kesehatan dan kecantikan cantik lainnya',
                'children' => [
                    [
                        'name' => 'Skincare',
                        'tagline' => 'Koleksi Skincare',
                        'description' => 'Berbagai Skincare pakaian terbaru'
                    ],
                    [
                        'name' => 'Suplemen',
                        'tagline' => 'Koleksi Suplemen',
                        'description' => 'Berbagai Suplemen pakaian terbaru'
                    ]
                ]
            ]
        ];

        $imageHelper = new ImageHelper;

        foreach($categories as $category) {
            $parent = ProductCategory::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'tagline' => $category['tagline'],
                'description' => $category['description'],
                'image' => $imageHelper->storeAndResizeImage(
                    $imageHelper->createDummyImageWithTextSizeAndPosition(
                        250, 250, 'center', 'center', 'random', 'medium'), 'product-category', 250, 250
                ),
                'parent_id' => null
            ]);

            foreach($category['children'] as $child){ 
                ProductCategory::create([
                    'name' => $child['name'],
                    'slug' => Str::slug($child['name']),
                    'tagline' => $child['tagline'],
                    'description' => $child['description'],
                    'image' => $imageHelper->storeAndResizeImage(
                        $imageHelper->createDummyImageWithTextSizeAndPosition(
                            250, 250, 'center', 'center', 'random', 'medium'), 'product-category', 250, 250
                    ),
                    'parent_id' => $parent->id
                ]);
            }
        } 

    }
}
