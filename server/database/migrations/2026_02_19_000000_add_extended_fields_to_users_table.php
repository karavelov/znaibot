<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->date('birth_date')->nullable()->after('phone');
            $table->string('birth_place')->nullable()->after('birth_date');
            $table->string('citizenship')->nullable()->after('birth_place');
            $table->unsignedBigInteger('parent_father_id')->nullable()->after('citizenship');
            $table->unsignedBigInteger('parent_mother_id')->nullable()->after('parent_father_id');
            $table->string('doctor_name')->nullable()->after('parent_mother_id');
            $table->string('doctor_phone')->nullable()->after('doctor_name');

            $table->foreign('parent_father_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('parent_mother_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['parent_father_id']);
            $table->dropForeign(['parent_mother_id']);
            $table->dropColumn(['birth_date', 'birth_place', 'citizenship', 'parent_father_id', 'parent_mother_id', 'doctor_name', 'doctor_phone']);
        });
    }
};
