<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop pivot table first (foreign keys)
        Schema::dropIfExists('user_years');
        Schema::dropIfExists('members');
        Schema::dropIfExists('member_years');

        // Add members count to clubs
        Schema::table('clubs', function (Blueprint $table) {
            $table->string('members')->nullable()->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('clubs', function (Blueprint $table) {
            $table->dropColumn('members');
        });

        Schema::create('member_years', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->boolean('status');
            $table->timestamps();
        });

        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('user_years', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id');
            $table->unsignedBigInteger('year_id');
            $table->timestamps();
        });
    }
};
