<?php
// modules/EventParticipation/Infrastructure/Persistence/Migrations/2026_03_25_105500_create_event_participations_table.php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_participations', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->restrictOnDelete();
            $table->foreignUuid('event_id')->constrained('events')->restrictOnDelete();
            $table->foreignUuid('position_id')->constrained('event_staffing_positions')->restrictOnDelete();
            $table->foreignUuid('group_id')->nullable()->constrained('event_staffing_groups')->nullOnDelete();
            $table->string('employee_number')->nullable();
            $table->string('status')->default('active');
            $table->date('started_at')->nullable();
            $table->date('ended_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['user_id', 'event_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_participations');
    }
};
