<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // За ученици — към кой клас принадлежат
            $table->unsignedBigInteger('klas_id')->nullable()->after('doctor_phone');
            // За учители — класен ръководител на кой клас
            $table->unsignedBigInteger('homeroom_klas_id')->nullable()->after('klas_id');

            $table->foreign('klas_id')->references('id')->on('klasses')->onDelete('set null');
            $table->foreign('homeroom_klas_id')->references('id')->on('klasses')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['klas_id']);
            $table->dropForeign(['homeroom_klas_id']);
            $table->dropColumn(['klas_id', 'homeroom_klas_id']);
        });
    }
};
