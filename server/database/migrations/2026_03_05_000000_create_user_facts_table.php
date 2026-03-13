<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_facts', function (Blueprint $table) {
            $table->id();
            $table->text('text');
            $table->unsignedBigInteger('userid')->nullable();
            $table->foreign('userid')->references('id')->on('users')->nullOnDelete();
            $table->timestamps();

            $table->index('userid');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_facts');
    }
};
