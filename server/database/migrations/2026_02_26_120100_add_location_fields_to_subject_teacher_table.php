<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subject_teacher', function (Blueprint $table) {
            $table->string('room', 64)->nullable()->after('teacher_id');
            $table->unsignedTinyInteger('floor')->nullable()->after('room');
            $table->decimal('map_x', 8, 5)->nullable()->after('floor');
            $table->decimal('map_y', 8, 5)->nullable()->after('map_x');

            $table->index('teacher_id');
        });
    }

    public function down(): void
    {
        Schema::table('subject_teacher', function (Blueprint $table) {
            $table->dropColumn(['room', 'floor', 'map_x', 'map_y']);
        });
    }
};
