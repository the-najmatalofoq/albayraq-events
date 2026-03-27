<?php
// modules/EventPositionApplication/Infrastructure/Persistence/Migrations/2026_03_26_000011_create_event_position_applications_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('event_position_applications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->foreignUuid('position_id')->constrained('event_staffing_positions')->cascadeOnDelete();
            $table->string('status');
            $table->decimal('ranking_score', 8, 2)->default(0);
            $table->timestamp('applied_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->uuid('reviewed_by')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('reviewed_by')->references('id')->on('users')->nullOnDelete();
            
            $table->unique(['user_id', 'position_id'], 'user_position_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_position_applications');
    }
};
