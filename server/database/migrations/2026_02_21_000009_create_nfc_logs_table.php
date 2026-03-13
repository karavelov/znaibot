<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nfc_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // null = непознат чип
            $table->string('nfc_id');                         // суровото ID от четеца
            $table->enum('reader', ['door_in', 'door_out', 'robot']); // кой четец
            $table->timestamp('read_at');                     // кога е прочетено
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['user_id', 'reader', 'read_at']);
            $table->index('nfc_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nfc_logs');
    }
};
