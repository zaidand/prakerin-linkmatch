<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // tambahkan setelah 'id'
            $table->foreignId('role_id')
                ->after('id')
                ->constrained()
                ->cascadeOnDelete();

            $table->enum('status', ['pending', 'active', 'rejected'])
                ->default('active') // untuk memudahkan pengujian awal
                ->after('password');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('role_id');
            $table->dropColumn('status');
        });
    }
};
