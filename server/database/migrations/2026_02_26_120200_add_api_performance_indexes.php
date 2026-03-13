<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->index(['role', 'status'], 'users_role_status_idx');
            $table->index('klas_id', 'users_klas_id_idx');
            $table->index('nfc_id', 'users_nfc_id_idx');
        });

        Schema::table('nfc_logs', function (Blueprint $table) {
            $table->index(['created_at', 'user_id'], 'nfc_logs_created_user_idx');
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->index(['klas_id', 'day_of_week'], 'schedules_klas_day_idx');
        });
    }

    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropIndex('schedules_klas_day_idx');
        });

        Schema::table('nfc_logs', function (Blueprint $table) {
            $table->dropIndex('nfc_logs_created_user_idx');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_role_status_idx');
            $table->dropIndex('users_klas_id_idx');
            $table->dropIndex('users_nfc_id_idx');
        });
    }
};
