<?php
// modules/EventStaffingPosition/Infrastructure/Persistence/Migrations/2026_03_25_103000_create_event_staffing_positions_table.php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_staffing_positions', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('event_id')->constrained('events')->cascadeOnDelete();
            $table->json('title');
            $table->decimal('wage_amount', 10, 2);
            $table->string('wage_type');
            $table->unsignedInteger('headcount');
            $table->json('requirements')->nullable();
            $table->boolean('is_announced')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_staffing_positions');
    }
};
