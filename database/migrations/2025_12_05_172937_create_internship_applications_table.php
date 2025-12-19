<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('internship_applications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('industry_id')->constrained()->cascadeOnDelete();
            $table->foreignId('industry_quota_id')
                ->nullable()
                ->constrained('industry_quotas')
                ->nullOnDelete();

            // status proses pengajuan
            $table->enum('status', [
                'waiting_teacher',   // baru diajukan, menunggu verifikasi guru
                'revision',          // dikembalikan ke siswa untuk revisi
                'waiting_admin',     // disetujui guru, menunggu admin
                'waiting_industry',  // ditetapkan admin, menunggu konfirmasi industri
                'accepted',          // diterima industri
                'rejected',          // ditolak (oleh guru / admin / industri)
            ])->default('waiting_teacher');

            // data pendukung pengajuan
            $table->decimal('gpa', 4, 2)->nullable(); // nilai rata-rata (opsional)
            $table->text('interest')->nullable();     // minat / motivasi
            $table->text('additional_info')->nullable();

            // catatan tiap pihak
            $table->text('teacher_note')->nullable();
            $table->text('admin_note')->nullable();
            $table->text('industry_note')->nullable();

            // timestamp tiap fase (opsional)
            $table->timestamp('teacher_verified_at')->nullable();
            $table->timestamp('admin_assigned_at')->nullable();
            $table->timestamp('industry_confirmed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internship_applications');
    }
};
