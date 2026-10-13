<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            // 4 Makanan (Foods)
            [
                'name' => 'Nasi Goreng Spesial RestoUs',
                'category' => 'food',
                'price' => 32000,
                'description' => 'Nasi goreng aromatik dengan topping ayam suwir, udang, telur mata sapi, dan kerupuk renyah.',
                'daily_quota' => 50,
                'is_available' => true,
                'image_url' => 'https://images.unsplash.com/photo-1603133872878-684f208fb84b?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Ayam Bakar Madu Taliwang',
                'category' => 'food',
                'price' => 42000,
                'description' => 'Ayam bakar dengan marinasi bumbu madu pedas manis khas, disajikan dengan lalapan segar.',
                'daily_quota' => 50,
                'is_available' => true,
                'image_url' => 'https://images.unsplash.com/photo-1626082927389-6cd097cdc6ec?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Beef Steak Wagyu Tenderloin',
                'category' => 'food',
                'price' => 85000,
                'description' => 'Daging sapi wagyu empuk dengan saus blackpepper, kentang goreng renyah dan tumis buncis jagung.',
                'daily_quota' => 50,
                'is_available' => true,
                'image_url' => 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Spaghetti Creamy Carbonara',
                'category' => 'food',
                'price' => 45000,
                'description' => 'Pasta spaghetti saus krim kental lembut dengan smoked beef gurih dan taburan keju parmesan.',
                'daily_quota' => 50,
                'is_available' => true,
                'image_url' => 'https://images.unsplash.com/photo-1612874742237-6526221588e3?auto=format&fit=crop&w=600&q=80',
            ],

            // 4 Minuman (Drinks)
            [
                'name' => 'Es Teh Manis Jasmine',
                'category' => 'drink',
                'price' => 8000,
                'description' => 'Seduhan teh melati wangi dan segar dengan pemanis alami dan es batu menyegarkan dahaga.',
                'daily_quota' => 50,
                'is_available' => true,
                'image_url' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Jus Alpukat Kocok',
                'category' => 'drink',
                'price' => 22000,
                'description' => 'Buah alpukat mentega segar dikocok kental dengan lumuran susu cokelat manis premium.',
                'daily_quota' => 50,
                'is_available' => true,
                'image_url' => 'https://images.unsplash.com/photo-1623065422902-30a2d299bbe4?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Kopi Susu Gula Aren',
                'category' => 'drink',
                'price' => 20000,
                'description' => 'Espresso arabica-robusta pilihan berpadu susu segar dingin dan cairan gula aren organik.',
                'daily_quota' => 50,
                'is_available' => true,
                'image_url' => 'https://images.unsplash.com/photo-1517701604599-bb29b565090c?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Lemon Tea Segar',
                'category' => 'drink',
                'price' => 14000,
                'description' => 'Perpaduan teh dingin dengan sari lemon segar asli yang memberi kesegaran instan.',
                'daily_quota' => 50,
                'is_available' => true,
                'image_url' => 'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?auto=format&fit=crop&w=600&q=80',
            ],
        ];

        foreach ($menus as $menu) {
            Menu::firstOrCreate(['name' => $menu['name']], $menu);
        }
    }
}
