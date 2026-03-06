<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['name' => 'Laptop Gaming ASUS ROG',  'qty' => 12, 'category' => 'Elektronik', 'min_stock' => 5],
            ['name' => 'Jaket Hoodie WGG 2026',   'qty' => 3,  'category' => 'Pakaian',    'min_stock' => 5],
            ['name' => 'Snack Box Gathering',      'qty' => 0,  'category' => 'Makanan',    'min_stock' => 10],
            ['name' => 'Toolkit Multimedia',       'qty' => 8,  'category' => 'Peralatan',  'min_stock' => 4],
            ['name' => 'Masker Kain WGG',          'qty' => 25, 'category' => 'Kesehatan',  'min_stock' => 8],
            ['name' => 'Tote Bag Petra 2026',      'qty' => 4,  'category' => 'Pakaian',    'min_stock' => 6],
            ['name' => 'Wireless Earbuds',         'qty' => 7,  'category' => 'Elektronik', 'min_stock' => 5],
            ['name' => 'Vitamin C 1000mg',         'qty' => 2,  'category' => 'Kesehatan',  'min_stock' => 10],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }
    }
}
