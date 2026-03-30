<?php

namespace Database\Seeders;

use App\Models\BusinessesTypes;
use Illuminate\Database\Seeder;

class BusinessesTypesSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            'Салон красоты',
            'Барбершоп',
            'Массажный салон',
            'Стоматология',
            'Косметология',
            'Фитнес-клуб',
            'Йога-студия',
            'Медицинский центр',
            'Автосервис',
            'Ресторан',
            'Кафе',
            'Отель',
        ];

        foreach ($types as $name) {
            BusinessesTypes::query()->firstOrCreate(['name' => $name]);
        }
    }
}
