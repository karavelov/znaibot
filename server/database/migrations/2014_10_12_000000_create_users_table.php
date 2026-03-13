<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('username')->nullable();
            $table->string('city')->nullable();
            $table->integer('pkod')->nullable();
            $table->string('address')->nullable();
            $table->string('firma_ime')->nullable();
            $table->string('firma_eik')->nullable();
            $table->string('firma_adres')->nullable();
            $table->enum('firma_dds', ['1', '0'])->default('0');
            $table->enum('firma_is', ['1', '0'])->default('0');
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();
            $table->string('useragent')->nullable();
            $table->text('image')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->unique();
            $table->enum('role', ['admin', 'vendor', 'user'])->default('user');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
