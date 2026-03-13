<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nfc_readers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('type', ['door_in', 'door_out', 'robot', 'other'])->default('other');
            $table->timestamps();
        });

        // Стандартни четци по подразбиране
        DB::table('nfc_readers')->insert([
            ['id' => 1, 'title' => 'Знайбот',      'type' => 'robot',    'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'title' => 'Вход (врата)',  'type' => 'door_in',  'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'title' => 'Изход (врата)', 'type' => 'door_out', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('nfc_readers');
    }
};
