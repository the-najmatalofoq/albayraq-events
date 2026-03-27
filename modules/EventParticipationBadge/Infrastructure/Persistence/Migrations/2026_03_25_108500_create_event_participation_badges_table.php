<?php
// modules/EventParticipationBadge/Infrastructure/Persistence/Migrations/2026_03_25_108500_create_event_participation_badges_table.php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_participation_badges', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('event_participation_id')->unique()->constrained('event_participations')->cascadeOnDelete();
            $table->json('badge_data')->nullable();
            $table->timestamp('generated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_participation_badges');
    }
};
