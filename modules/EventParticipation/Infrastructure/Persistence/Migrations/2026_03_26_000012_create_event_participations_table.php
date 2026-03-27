<?php
// modules/EventParticipation/Infrastructure/Persistence/Migrations/2026_03_26_000012_create_event_participations_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('event_participations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->foreignUuid('event_id')->constrained('events')->cascadeOnDelete();
            $table->foreignUuid('position_id')->constrained('event_staffing_positions')->cascadeOnDelete();
            $table->foreignUuid('group_id')->nullable()->constrained('event_staffing_groups')->nullOnDelete();
            $table->string('employee_number')->nullable();
            $table->string('status');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();

            $table->unique(['user_id', 'event_id'], 'user_event_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_participations');
    }
};
