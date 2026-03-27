<?php
// modules/EventRoleCapability/Infrastructure/Persistence/Migrations/2026_03_25_104500_create_event_role_capabilities_table.php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_role_capabilities', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('event_id')->constrained('events')->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('capability');
            $table->timestamps();

            $table->unique(['event_id', 'user_id', 'capability']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_role_capabilities');
    }
};
