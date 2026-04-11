<?php
// modules/EventRoleCapability/Infrastructure/Persistence/Migrations/2026_04_11_100000_fix_and_refactor_capabilities_table.php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {

        Schema::create('event_role_capabilities', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('event_role_assignment_id')->constrained('event_role_assignments')->cascadeOnDelete();
            $table->string('capability_key');
            $table->boolean('is_granted')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['event_role_assignment_id', 'capability_key'], 'idx_assignment_capability_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_role_capabilities');
    }
};
