<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nfc_id')->nullable()->unique()->after('homeroom_klas_id');
        });

        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'vendor', 'user', 'student', 'teacher', 'parent', 'security') DEFAULT 'user'");
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('nfc_id');
        });

        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'vendor', 'user', 'student', 'teacher', 'parent') DEFAULT 'user'");
    }
};
