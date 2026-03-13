<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('klas_semesters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('klas_id');
            $table->tinyInteger('semester'); // 1 или 2
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();

            $table->foreign('klas_id')->references('id')->on('klasses')->onDelete('cascade');
            $table->unique(['klas_id', 'semester']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('klas_semesters');
    }
};
