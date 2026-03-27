<?php
// modules/ParticipationEvaluation/Infrastructure/Persistence/Migrations/2026_03_26_000017_create_participation_evaluations_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('participation_evaluations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('event_participation_id')->unique()->constrained('event_participations')->cascadeOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->json('feedback')->nullable();
            $table->uuid('evaluated_by');
            $table->timestamps();

            $table->foreign('evaluated_by')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participation_evaluations');
    }
};
