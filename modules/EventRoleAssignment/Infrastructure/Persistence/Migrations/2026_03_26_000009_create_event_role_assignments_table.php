<?php
// modules/EventRoleAssignment/Infrastructure/Persistence/Migrations/2026_03_26_000009_create_event_role_assignments_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('event_role_assignments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('event_id')->constrained('events')->cascadeOnDelete();
            $table->uuid('user_id');
            $table->uuid('role_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('role_id')->references('id')->on('roles')->cascadeOnDelete();
            
            $table->unique(['event_id', 'user_id', 'role_id'], 'event_user_role_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_role_assignments');
    }
};
