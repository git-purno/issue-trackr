<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('change_requests', function (Blueprint $table) {
        $table->id();

        $table->string('title');
        $table->text('description');

        $table->text('justification');
        $table->text('risk_analysis');
        $table->text('affected_systems');

        $table->string('impact_level');

        $table->string('status')->default('submitted');

        $table->unsignedBigInteger('user_id');

        $table->timestamp('scheduled_at')->nullable();
        $table->text('rollback_plan')->nullable();

        $table->boolean('verified')->default(false);

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('change_requests');
    }
};
