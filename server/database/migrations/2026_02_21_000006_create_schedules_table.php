<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('klas_id');
            $table->tinyInteger('semester');             // 1 или 2
            $table->tinyInteger('day_of_week');          // 1=Пон, 2=Вт, 3=Ср, 4=Чет, 5=Пет
            $table->tinyInteger('period');               // 1–8 учебен час
            $table->unsignedBigInteger('subject_teacher_id')->nullable();
            $table->timestamps();

            $table->foreign('klas_id')->references('id')->on('klasses')->onDelete('cascade');
            $table->foreign('subject_teacher_id')->references('id')->on('subject_teacher')->onDelete('set null');
            $table->unique(['klas_id', 'semester', 'day_of_week', 'period']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
