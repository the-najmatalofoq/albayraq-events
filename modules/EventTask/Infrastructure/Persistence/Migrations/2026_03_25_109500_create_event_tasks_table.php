<?php
// modules/EventTask/Infrastructure/Persistence/Migrations/2026_03_25_109500_create_event_tasks_table.php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_tasks', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('event_id')->constrained('events')->cascadeOnDelete();
            $table->foreignUuid('assigned_to')->constrained('users')->restrictOnDelete();
            $table->foreignUuid('group_id')->nullable()->constrained('event_staffing_groups')->nullOnDelete();
            $table->json('title');
            $table->json('description')->nullable();
            $table->timestamp('due_at')->nullable();
            $table->decimal('location_latitude', 10, 7)->nullable();
            $table->decimal('location_longitude', 10, 7)->nullable();
            $table->string('status')->default('pending');
            $table->foreignUuid('created_by')->constrained('users')->restrictOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_tasks');
    }
};
