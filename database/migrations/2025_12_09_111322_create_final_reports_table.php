<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('final_reports', function (Blueprint $table) {
            $table->id();

            $table->foreignId('internship_application_id')
                ->constrained()
                ->cascadeOnDelete()
                ->unique(); // satu aplikasi satu laporan

            $table->string('file_path');        // path file laporan (pdf/docx)
            $table->text('summary')->nullable(); // ringkasan / abstrak

            $table->enum('status', [
                'waiting_teacher',  // menunggu penilaian guru
                'revision',         // dikembalikan untuk revisi
                'graded',           // sudah dinilai
            ])->default('waiting_teacher');

            $table->decimal('teacher_score', 5, 2)->nullable(); // nilai laporan dari guru
            $table->text('teacher_comment')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('graded_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('final_reports');
    }
};
