<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            // Nullable because it gets assigned during Phase 3 admin approval
            if (!Schema::hasColumn('enrollments', 'block_id')) {
                $table->foreignId('block_id')->nullable()->constrained('blocks')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            if (Schema::hasColumn('enrollments', 'block_id')) {
                $table->dropForeign(['block_id']);
                $table->dropColumn('block_id');
            }
        });
    }
};