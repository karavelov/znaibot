<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('clubs', function (Blueprint $table) {
        $table->id();
        $table->string('clubname'); // Име на клуба
        $table->string('mentor');   // Ментор/Учител
        $table->text('participants'); // Участници (може да е бройка или списък с имена)
        $table->text('description')->nullable(); // Допълнително описание
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clubs');
    }
};
