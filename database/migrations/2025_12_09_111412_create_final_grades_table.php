<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('final_grades', function (Blueprint $table) {
            $table->id();

            $table->foreignId('internship_application_id')
                ->constrained()
                ->cascadeOnDelete()
                ->unique();

            // komponen nilai
            $table->decimal('industry_score', 5, 2)->nullable(); // dari industry_assessments.overall_score
            $table->decimal('report_score', 5, 2)->nullable();   // dari final_reports.teacher_score
            $table->decimal('attendance_score', 5, 2)->nullable(); // misal dari rekap hadir (bisa diisi manual)

            // bobot (0-100) - editable oleh guru
            $table->unsignedTinyInteger('weight_industry')->default(40);
            $table->unsignedTinyInteger('weight_report')->default(40);
            $table->unsignedTinyInteger('weight_attendance')->default(20);

            $table->decimal('final_score', 5, 2)->nullable();
            $table->string('grade_letter', 2)->nullable(); // A, B, C, dll

            $table->boolean('locked')->default(false); // jika sudah final, sebaiknya tidak diubah

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('final_grades');
    }
};
