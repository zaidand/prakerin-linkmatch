<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 1) Ubah kolom status jadi VARCHAR(50)
        DB::statement("
            ALTER TABLE internship_applications
            MODIFY status VARCHAR(50) NOT NULL DEFAULT 'waiting_teacher_verification'
        ");

        // 2) Migrasi nilai status lama -> status baru
        DB::table('internship_applications')
            ->where('status', 'waiting_teacher')
            ->update(['status' => 'waiting_teacher_verification']);

        DB::table('internship_applications')
            ->where('status', 'waiting_admin')
            ->update(['status' => 'approved_by_teacher']);

        DB::table('internship_applications')
            ->where('status', 'waiting_industry')
            ->update(['status' => 'assigned_by_admin']);

        // optional: kalau masih ada revision, kamu mau anggap apa?
        // aman sementara: balikin ke menunggu guru
        DB::table('internship_applications')
            ->where('status', 'revision')
            ->update(['status' => 'waiting_teacher_verification']);
    }

    public function down(): void
    {
        // tidak perlu dibalikkan
    }
};
