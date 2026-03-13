<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('allergens', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('color', 7)->default('#dc3545');
            $table->timestamps();
        });

        // 14-те основни алергена на ЕС
        DB::table('allergens')->insert([
            ['name' => 'Глутен',          'description' => 'Пшеница, ечемик, ръж, овес и техни производни', 'color' => '#f59e0b', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ракообразни',      'description' => 'Скариди, омари, раци и техни производни',       'color' => '#ef4444', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Яйца',            'description' => 'Яйца и продукти от тях',                        'color' => '#eab308', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Риба',            'description' => 'Риба и рибни продукти',                         'color' => '#3b82f6', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Фъстъци',         'description' => 'Фъстъци и продукти от тях',                    'color' => '#a16207', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Соя',             'description' => 'Соя и соеви продукти',                         'color' => '#16a34a', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Мляко',           'description' => 'Краве мляко, лактоза и млечни продукти',       'color' => '#60a5fa', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Черупкови ядки',  'description' => 'Бадеми, лешници, орехи, кашу, пекани, шамфъстъци', 'color' => '#92400e', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Целина',          'description' => 'Целина и продукти от нея',                     'color' => '#65a30d', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Горчица',         'description' => 'Горчица и горчични продукти',                  'color' => '#ca8a04', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Сусам',           'description' => 'Сусамово семе и продукти от него',             'color' => '#d97706', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Сулфити',         'description' => 'Серен диоксид и сулфити (> 10 mg/kg)',         'color' => '#7c3aed', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Лупина',          'description' => 'Лупинови семена и продукти',                   'color' => '#db2777', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Мекотели',        'description' => 'Стриди, миди, калмари и продукти от тях',      'color' => '#0891b2', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('allergens');
    }
};
