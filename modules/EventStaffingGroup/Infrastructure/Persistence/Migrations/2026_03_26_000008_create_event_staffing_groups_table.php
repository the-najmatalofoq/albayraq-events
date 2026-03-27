<?php
// modules/EventStaffingGroup/Infrastructure/Persistence/Migrations/2026_03_26_000008_create_event_staffing_groups_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('event_staffing_groups', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('event_id')->constrained('events')->cascadeOnDelete();
            $table->json('name');
            $table->uuid('leader_id');
            $table->string('color', 7);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('leader_id')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_staffing_groups');
    }
};
