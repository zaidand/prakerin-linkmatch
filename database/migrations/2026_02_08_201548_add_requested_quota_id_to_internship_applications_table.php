<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('internship_applications', function (Blueprint $table) {
            $table->foreignId('requested_quota_id')
                ->nullable()
                ->after('industry_quota_id')
                ->constrained('industry_quotas')
                ->nullOnDelete();
        });
    // 1) Backfill: nilai lama industry_quota_id dianggap sebagai kuota yang dipilih siswa (reservasi awal).
        DB::table('internship_applications')
            ->whereNull('requested_quota_id')
            ->whereNotNull('industry_quota_id')
            ->update(['requested_quota_id' => DB::raw('industry_quota_id')]);

        // 2) Setelah ada requested_quota_id, industry_quota_id dipakai khusus untuk "alokasi resmi" admin.
        // Jadi untuk fase awal (sebelum admin assign), kosongkan industry_quota_id.
        DB::table('internship_applications')
            ->whereIn('status', [
                'waiting_teacher_verification',
                'approved_by_teacher',
            ])
            ->update(['industry_quota_id' => null]);
    }

    public function down(): void
    {
        Schema::table('internship_applications', function (Blueprint $table) {
            $table->dropConstrainedForeignId('requested_quota_id');
        });
    }
};
