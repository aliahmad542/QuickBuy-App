<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Laptops
        Product::create([
            'name' => 'QuantumBook Pro',
            'price' => '1500',
            'store_id' => '1',
            'image' => '\products\laptop1.png',
            'description' => 'High performance laptop perfect for both work and play, featuring the latest technology.',
            'quantity' => '50',
        ]);
        Product::create([
            'name' => 'NanoTech X',
            'price' => '1200',
            'store_id' => '1',
            'image' => '\products\laptop2.png',
            'description' => 'Sleek design with powerful capabilities, ideal for on-the-go professionals.',
            'quantity' => '70',
        ]);
        Product::create([
            'name' => 'SkyNet 3000',
            'price' => '1000',
            'store_id' => '1',
            'image' => '\products\laptop3.png',
            'description' => 'Affordable yet powerful, this laptop is designed to meet all your daily computing needs.',
            'quantity' => '40',
        ]);
        Product::create([
            'name' => 'TechMaster Elite',
            'price' => '1800',
            'store_id' => '1',
            'image' => '\products\laptop4.png',
            'description' => 'Ultimate laptop for professionals who demand the best in performance and style.',
            'quantity' => '30',
        ]);

        // Food
        Product::create([
            'name' => 'Gourmet Hamburger',
            'price' => '10',
            'store_id' => '2',
            'image' => '\products\KFC2.png',
            'description' => 'Juicy hamburger with gourmet toppings that will tantalize your taste buds.',
            'quantity' => '100',
        ]);
        Product::create([
            'name' => 'Cheesy Pizza',
            'price' => '8',
            'store_id' => '2',
            'image' => '\products\KFC3.png',
            'description' => 'Delicious pizza loaded with cheese, perfect for any pizza lover.',
            'quantity' => '80',
        ]);
        Product::create([
            'name' => 'Fried KFC Wings',
            'price' => '12',
            'store_id' => '2',
            'image' => '\products\KFC1.png',
            'description' => 'Crispy fried wings with secret spices that will leave you craving for more.',
            'quantity' => '60',
        ]);

        // More Food
        Product::create([
            'name' => 'Rice and Meat Delight',
            'price' => '15',
            'store_id' => '3',
            'image' => '\products\food1.png',
            'description' => 'Rich and flavorful rice paired with tender meat, a true culinary delight.',
            'quantity' => '50',
        ]);
        Product::create([
            'name' => 'Crispy Fried Potato',
            'price' => '5',
            'store_id' => '3',
            'image' => '\products\food2.png',
            'description' => 'Perfectly fried potato slices, crispy on the outside and soft on the inside.',
            'quantity' => '120',
        ]);
        Product::create([
            'name' => 'Hearty Meat and Rice',
            'price' => '20',
            'store_id' => '3',
            'image' => '\products\food3.png',
            'description' => 'Satisfying meal with tender meat and savory rice, perfect for hearty appetites.',
            'quantity' => '40',
        ]);

        // Cakes
        Product::create([
            'name' => 'Chocolate Heaven Cake',
            'price' => '25',
            'store_id' => '4',
            'image' => '\products\cake1.png',
            'description' => 'Decadent chocolate cake for chocoholics, rich and indulgent.',
            'quantity' => '30',
        ]);
        Product::create([
            'name' => 'Vanilla Dream Cake',
            'price' => '22',
            'store_id' => '4',
            'image' => '\products\cake2.png',
            'description' => 'Light and fluffy vanilla cake, a classic dessert favorite.',
            'quantity' => '25',
        ]);
        Product::create([
            'name' => 'Red Velvet Bliss',
            'price' => '27',
            'store_id' => '4',
            'image' => '\products\cake3.png',
            'description' => 'Classic red velvet cake with a rich cream cheese frosting.',
            'quantity' => '20',
        ]);
        Product::create([
            'name' => 'Fruit Fantasy Cake',
            'price' => '30',
            'store_id' => '4',
            'image' => '\products\cake4.png',
            'description' => 'Fresh fruit cake, a refreshing treat for any occasion.',
            'quantity' => '15',
        ]);

        // Books
        Product::create([
            'name' => 'Adventures in CodeLand',
            'price' => '35',
            'store_id' => '5',
            'image' => '\products\book2.png',
            'description' => 'A thrilling journey through the world of coding, perfect for aspiring programmers.',
            'quantity' => '50',
        ]);
        Product::create([
            'name' => 'Design Secrets Unveiled',
            'price' => '40',
            'store_id' => '5',
            'image' => '\products\book3.png',
            'description' => 'Unlock the secrets of great design, a must-read for design enthusiasts.',
            'quantity' => '45',
        ]);
    }
}
