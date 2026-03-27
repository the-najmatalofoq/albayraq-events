<?php
// modules/EventStaffingPositionRequirement/Infrastructure/Persistence/Migrations/2026_03_26_000013_create_event_staffing_position_requirements_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('event_staffing_position_requirements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('position_id')->constrained('event_staffing_positions')->cascadeOnDelete();
            $table->json('title');
            $table->boolean('is_required')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_staffing_position_requirements');
    }
};
