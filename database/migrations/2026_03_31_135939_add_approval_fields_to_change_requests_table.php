<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('change_requests', function (Blueprint $table) {
            $table->foreignId('analyst_id')->nullable()->after('user_id')->constrained('users')->nullOnDelete();
            $table->timestamp('analyst_approved_at')->nullable()->after('analyst_id');
            $table->foreignId('manager_id')->nullable()->after('analyst_approved_at')->constrained('users')->nullOnDelete();
            $table->timestamp('manager_approved_at')->nullable()->after('manager_id');
            $table->foreignId('admin_id')->nullable()->after('manager_approved_at')->constrained('users')->nullOnDelete();
            $table->timestamp('admin_approved_at')->nullable()->after('admin_id');
        });
    }

    public function down(): void
    {
        Schema::table('change_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('analyst_id');
            $table->dropConstrainedForeignId('manager_id');
            $table->dropConstrainedForeignId('admin_id');
            $table->dropColumn([
                'analyst_approved_at',
                'manager_approved_at',
                'admin_approved_at',
            ]);
        });
    }
};
