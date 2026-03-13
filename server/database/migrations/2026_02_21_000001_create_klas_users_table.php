<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('klas_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('klas_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('klas_id')->references('id')->on('klasses')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unique(['klas_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('klas_users');
    }
};
