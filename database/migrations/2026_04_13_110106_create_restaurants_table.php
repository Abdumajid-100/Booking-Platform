<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            $table->string('name');          // Название
            $table->string('cuisine');       // Тип кухни (итальянская, японская и т.д.)
            $table->integer('avg_check');    // Средний чек (сумма)
            $table->float('rating');         // Рейтинг (4.8)
            $table->integer('reviews_count');// Кол-во отзывов
            $table->string('address');       // Адрес
            $table->json('features');        // Доп. теги: ["wi-fi", "терраса", "детская зона"]
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};
