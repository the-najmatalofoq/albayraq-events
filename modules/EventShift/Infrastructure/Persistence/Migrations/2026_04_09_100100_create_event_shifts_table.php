<?php
// modules/EventShift/Infrastructure/Persistence/Migrations/2026_04_09_100100_create_event_shifts_table.php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_shifts', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('event_id')->constrained('events')->cascadeOnDelete();
            $table->foreignUuid('position_id')->constrained('event_staffing_positions')->cascadeOnDelete();
            $table->string('label');
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->unsignedInteger('max_assignees')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
            $table->unique(['event_id', 'position_id', 'start_at'], 'event_position_start_unique');
            $table->index(['event_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_shifts');
    }
};
