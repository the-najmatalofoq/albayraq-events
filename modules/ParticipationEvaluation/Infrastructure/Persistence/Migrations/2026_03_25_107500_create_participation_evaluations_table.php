<?php
// modules/ParticipationEvaluation/Infrastructure/Persistence/Migrations/2026_03_25_107500_create_participation_evaluations_table.php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('participation_evaluations', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('event_participation_id')->constrained('event_participations')->cascadeOnDelete();
            $table->foreignUuid('evaluator_id')->constrained('users')->restrictOnDelete();
            $table->date('date');
            $table->decimal('score', 3, 1);
            $table->text('notes')->nullable();
            $table->boolean('is_locked')->default(false);
            $table->timestamp('locked_at')->nullable();
            $table->timestamps();

            $table->unique(['event_participation_id', 'evaluator_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participation_evaluations');
    }
};
