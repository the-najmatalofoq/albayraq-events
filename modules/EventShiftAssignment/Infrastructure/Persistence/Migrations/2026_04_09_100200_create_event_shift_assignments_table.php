<?php
// modules/EventShiftAssignment/Infrastructure/Persistence/Migrations/2026_04_09_100200_create_event_shift_assignments_table.php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_shift_assignments', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('shift_id')->constrained('event_shifts')->cascadeOnDelete();
            $table->foreignUuid('participation_id')->constrained('event_participations')->cascadeOnDelete();
            $table->string('status')->default('assigned');
            $table->foreignUuid('assigned_by')->constrained('users')->restrictOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['shift_id', 'participation_id'], 'shift_participation_unique');
            $table->index(['participation_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_shift_assignments');
    }
};
