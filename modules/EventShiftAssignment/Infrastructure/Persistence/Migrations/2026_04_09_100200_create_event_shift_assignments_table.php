<?php
// modules/EventShiftAssignment/Infrastructure/Persistence/Migrations/2026_04_09_100200_create_event_shift_assignments_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('event_shift_assignments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('participation_id')
                ->constrained('event_participations')
                ->cascadeOnDelete();
            $table->foreignUuid('shift_id')
                ->constrained('event_shifts')
                ->cascadeOnDelete();
            $table->string('status')->default('active')->index();
            $table->timestamps();

            $table->unique(['participation_id', 'shift_id']);

            $table->index(['shift_id', 'status']);
            $table->index(['participation_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_shift_assignments');
    }
};
