<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();

        $products = [
            ['name' => 'Смартфон', 'price' => 29999.99, 'quantity' => 50],
            ['name' => 'Ноутбук', 'price' => 54999.99, 'quantity' => 30],
            ['name' => 'Наушники', 'price' => 4999.99, 'quantity' => 100],
            ['name' => 'Футболка', 'price' => 1999.99, 'quantity' => 200],
            ['name' => 'Джинсы', 'price' => 3999.99, 'quantity' => 150],
            ['name' => 'Роман', 'price' => 599.99, 'quantity' => 300],
            ['name' => 'Учебник', 'price' => 1299.99, 'quantity' => 250],
            ['name' => 'Диван', 'price' => 34999.99, 'quantity' => 20],
            ['name' => 'Стол', 'price' => 8999.99, 'quantity' => 40],
            ['name' => 'Стул', 'price' => 2999.99, 'quantity' => 80],
        ];

        foreach ($products as $index => $productData) {
            Product::create([
                'name' => $productData['name'],
                'description' => 'Описание для ' . $productData['name'],
                'price' => $productData['price'],
                'quantity' => $productData['quantity'],
                'category_id' => $categories[$index % count($categories)]->id,
            ]);
        }
    }
}
