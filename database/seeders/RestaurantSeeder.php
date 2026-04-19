<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Restaurant::create([
            'name' => 'Итальянский Дворик',
            'cuisine' => 'Итальянская',
            'avg_check' => 1200,
            'rating' => 4.7,
            'reviews_count' => 150,
            'address' => 'ул. Мира, 10',
            'features' => json_encode(['паста', 'уютно', 'вино']),
        ]);

        \App\Models\Restaurant::create([
            'name' => 'Бургер Лаб',
            'cuisine' => 'Американская',
            'avg_check' => 800,
            'rating' => 4.3,
            'reviews_count' => 450,
            'address' => 'пр. Ленина, 5',
            'features' => json_encode(['быстро', 'дешево', 'центр']),
        ]);
    }
}
