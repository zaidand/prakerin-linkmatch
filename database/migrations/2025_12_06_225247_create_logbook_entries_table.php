<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('logbook_entries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('internship_application_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->date('log_date'); // tanggal kegiatan
            $table->time('check_in_time')->nullable();   // jam hadir
            $table->time('check_out_time')->nullable();  // jam pulang

            $table->text('activity_description'); // deskripsi kegiatan
            $table->text('tools_used')->nullable();       // alat/bahan
            $table->text('competencies')->nullable();     // kompetensi yang diterapkan

            $table->string('evidence_path')->nullable();  // path file foto/dokumen

            $table->enum('status', [
                'waiting_validation',  // menunggu validasi pembimbing lapangan
                'approved',            // disetujui
                'rejected',            // ditolak/dikoreksi
            ])->default('waiting_validation');

            $table->text('industry_comment')->nullable(); // komentar pembimbing lapangan
            $table->timestamp('validated_at')->nullable(); // kapan divalidasi

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logbook_entries');
    }
};
