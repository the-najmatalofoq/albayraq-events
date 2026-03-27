<?php
// modules/ParticipationViolation/Infrastructure/Persistence/Migrations/2026_03_26_000018_create_participation_violations_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('participation_violations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('event_participation_id')->constrained('event_participations')->cascadeOnDelete();
            $table->foreignUuid('violation_type_id')->constrained('violation_types')->cascadeOnDelete();
            $table->json('description');
            $table->uuid('issued_by');
            $table->dateTime('occurred_at');
            $table->timestamps();

            $table->foreign('issued_by')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participation_violations');
    }
};
