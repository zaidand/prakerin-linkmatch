<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('internship_applications', function (Blueprint $table) {
            // Ubah kolom status menjadi string dengan panjang 50
            $table->string('status', 50)->default('waiting_teacher_verification')->change();
        });
    }

    public function down(): void
    {
        Schema::table('internship_applications', function (Blueprint $table) {
            // SESUAIKAN dengan tipe awal kamu.
            // Contoh kalau sebelumnya string(20):
            // $table->string('status', 20)->default('pending')->change();
        });
    }
};
