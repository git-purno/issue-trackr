<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('change_requests', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->text('justification');
            $table->text('risk_analysis');
            $table->text('affected_systems');
            $table->enum('impact_level', ['low', 'medium', 'high']);
            $table->enum('status', [
                'submitted',
                'analyst_approved',
                'manager_approved',
                'admin_approved',
                'scheduled',
                'completed',
            ])->default('submitted');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->dateTime('scheduled_at')->nullable();
            $table->text('rollback_plan')->nullable();
            $table->boolean('verified')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('change_requests');
    }
};
