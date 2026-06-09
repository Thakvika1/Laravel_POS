<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['name' => 'Espresso',        'price' => 3.50,  'qty' => 100, 'category' => 'Beverages', 'description' => 'Rich single-shot espresso'],
            ['name' => 'Cappuccino',      'price' => 4.50,  'qty' => 100, 'category' => 'Beverages', 'description' => 'Espresso with steamed milk foam'],
            ['name' => 'Latte',           'price' => 4.75,  'qty' => 100, 'category' => 'Beverages', 'description' => 'Espresso with steamed milk'],
            ['name' => 'Croissant',       'price' => 3.00,  'qty' => 40,  'category' => 'Pastries',  'description' => 'Buttery flaky croissant'],
            ['name' => 'Blueberry Muffin','price' => 2.75,  'qty' => 30,  'category' => 'Pastries',  'description' => 'Fresh baked blueberry muffin'],
            ['name' => 'Avocado Toast',   'price' => 9.50,  'qty' => 25,  'category' => 'Food',      'description' => 'Sourdough with avocado spread'],
            ['name' => 'Club Sandwich',   'price' => 11.00, 'qty' => 20,  'category' => 'Food',      'description' => 'Triple-decker club sandwich'],
            ['name' => 'Caesar Salad',    'price' => 10.50, 'qty' => 20,  'category' => 'Food',      'description' => 'Crisp romaine with Caesar dressing'],
            ['name' => 'Orange Juice',    'price' => 4.00,  'qty' => 50,  'category' => 'Beverages', 'description' => 'Freshly squeezed orange juice'],
            ['name' => 'Mineral Water',   'price' => 2.00,  'qty' => 200, 'category' => 'Beverages', 'description' => 'Still mineral water 500ml'],
            ['name' => 'Chocolate Cake',  'price' => 5.50,  'qty' => 15,  'category' => 'Pastries',  'description' => 'Rich chocolate layer cake'],
            ['name' => 'Green Tea',       'price' => 3.25,  'qty' => 80,  'category' => 'Beverages', 'description' => 'Premium loose-leaf green tea'],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
