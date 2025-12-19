<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('industry_assessments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('internship_application_id')
                ->constrained()
                ->cascadeOnDelete()
                ->unique(); // satu aplikasi prakerin, satu penilaian industri

            // skor (0 - 100)
            $table->unsignedTinyInteger('discipline')->nullable();      // sikap & kedisiplinan
            $table->unsignedTinyInteger('technical_skill')->nullable(); // kemampuan teknis
            $table->unsignedTinyInteger('teamwork')->nullable();        // kerjasama
            $table->unsignedTinyInteger('communication')->nullable();   // komunikasi
            $table->unsignedTinyInteger('responsibility')->nullable();  // tanggung jawab

            $table->decimal('overall_score', 5, 2)->nullable(); // rata-rata / skor akhir
            $table->text('notes')->nullable();                  // komentar & rekomendasi

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('industry_assessments');
    }
};
