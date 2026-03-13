<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nfc_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('nfc_reader_id')->nullable()->after('nfc_id');
            $table->foreign('nfc_reader_id')->references('id')->on('nfc_readers')->onDelete('set null');
        });

        // Прехвърли съществуващи enum стойности към FK (ако има данни)
        if (Schema::hasColumn('nfc_logs', 'reader')) {
            \DB::statement("
                UPDATE nfc_logs SET nfc_reader_id =
                    CASE reader
                        WHEN 'robot'    THEN 1
                        WHEN 'door_in'  THEN 2
                        WHEN 'door_out' THEN 3
                    END
                WHERE reader IS NOT NULL
            ");

            Schema::table('nfc_logs', function (Blueprint $table) {
                $table->dropColumn('reader');
            });
        }
    }

    public function down(): void
    {
        Schema::table('nfc_logs', function (Blueprint $table) {
            $table->dropForeign(['nfc_reader_id']);
            $table->dropColumn('nfc_reader_id');
            $table->enum('reader', ['door_in', 'door_out', 'robot'])->nullable()->after('nfc_id');
        });
    }
};
