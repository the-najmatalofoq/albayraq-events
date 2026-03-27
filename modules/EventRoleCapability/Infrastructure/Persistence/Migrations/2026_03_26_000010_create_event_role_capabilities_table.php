<?php
// modules/EventRoleCapability/Infrastructure/Persistence/Migrations/2026_03_26_000010_create_event_role_capabilities_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('event_role_capabilities', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('assignment_id')->constrained('event_role_assignments')->cascadeOnDelete();
            $table->string('capability_key');
            $table->boolean('is_granted')->default(true);
            $table->timestamps();

            $table->unique(['assignment_id', 'capability_key'], 'assignment_capability_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_role_capabilities');
    }
};
