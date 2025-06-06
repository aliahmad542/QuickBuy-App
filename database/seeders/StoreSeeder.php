<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Store;


class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Store::create([
            'name' => 'laptops Heaven',
            'image' => '/stores/LaptopStore.png'
        ]);

        Store::create([
            'name' => 'Cake Center',
            'image' => '/stores/CakeStore.png'
        ]);

        Store::create([
            'name' => 'KFC',
            'image' => '/stores/KFC_Store.png'
        ]);

        Store::create([
            'name' => 'Fast Food',
            'image' => '\stores\FoodStore.png'
        ]);

        Store::create([
            'name' => 'Book 4 U',
            'image' => '\stores\BookStore.png'
        ]);
    }
}
