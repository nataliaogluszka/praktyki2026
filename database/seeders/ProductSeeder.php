<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        
        // Kategorie
        $categories = [
            ['name' => 'Koszulki'],
            ['name' => 'Bluzy'],
            ['name' => 'Kurtki'],
            ['name' => 'Buty'],
            ['name' => 'Spodnie'],
        ];

        // foreach ($categories as &$category) {
        //     $category['created_at'] = Carbon::now();
        //     $category['updated_at'] = Carbon::now();
        // }

        DB::table('categories')->insert($categories);

        // Pobranie ID kategorii
        $koszulki = DB::table('categories')->where('name', 'Koszulki')->first()->id;
        $bluzy = DB::table('categories')->where('name', 'Bluzy')->first()->id;
        $kurtki = DB::table('categories')->where('name', 'Kurtki')->first()->id;
        $buty = DB::table('categories')->where('name', 'Buty')->first()->id;
        $spodnie = DB::table('categories')->where('name', 'Spodnie')->first()->id;

        // Produkty
        $products = [
            [
                'category_id' => $koszulki,
                'name' => 'Nike Sportswear Club T-Shirt',
                'description' => 'Klasyczna koszulka Nike z bawełny, regular fit.',
                'image' => '1.jpg',
                'price' => 99.99,
            ],
            [
                'category_id' => $koszulki,
                'name' => 'Adidas Essentials Logo T-Shirt',
                'description' => 'Koszulka Adidas z dużym logo na piersi.',
                'image' => '2.jpg',
                'price' => 89.99,
            ],
            [
                'category_id' => $bluzy,
                'name' => 'Puma Essentials Hoodie',
                'description' => 'Bluza z kapturem Puma, miękka bawełna.',
                'image' => '3.jpg',
                'price' => 199.99,
            ],
            [
                'category_id' => $bluzy,
                'name' => 'Tommy Hilfiger Core Hoodie',
                'description' => 'Bluza premium z haftowanym logo Tommy Hilfiger.',
                'image' => '4.jpg',
                'price' => 349.99,
            ],
            [
                'category_id' => $kurtki,
                'name' => 'The North Face 1996 Retro Nuptse Jacket',
                'description' => 'Puchowa kurtka zimowa The North Face.',
                'image' => '5.jpg',
                'price' => 1299.99,
            ],
            [
                'category_id' => $kurtki,
                'name' => 'Columbia Powder Lite Jacket',
                'description' => 'Lekka kurtka outdoorowa Columbia.',
                'image' => '6.jpg',
                'price' => 499.99,
            ],
            [
                'category_id' => $buty,
                'name' => 'Nike Air Force 1',
                'description' => 'Kultowe sneakersy Nike Air Force 1.',
                'image' => '7.jpg',
                'price' => 549.99,
            ],
            [
                'category_id' => $buty,
                'name' => 'Adidas Ultraboost 22',
                'description' => 'Buty biegowe Adidas Ultraboost z technologią Boost.',
                'image' => '8.jpg',
                'price' => 699.99,
            ],
            [
                'category_id' => $spodnie,
                'name' => 'Levi’s 501 Original Jeans',
                'description' => 'Klasyczne jeansy Levi’s 501, prosty krój.',
                'image' => '9.jpg',
                'price' => 399.99,
            ],
            [
                'category_id' => $spodnie,
                'name' => 'Wrangler Texas Stretch Jeans',
                'description' => 'Jeansy Wrangler z elastycznego denimu.',
                'image' => '10.jpg',
                'price' => 279.99,
            ],
        ];

        foreach ($products as &$product) {
            $product['created_at'] = Carbon::now();
            $product['updated_at'] = Carbon::now();
        }

        DB::table('products')->insert($products);
    }
}
