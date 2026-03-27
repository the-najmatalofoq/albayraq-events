<?php
// modules/EventTask/Infrastructure/Persistence/Migrations/2026_03_26_000014_create_event_tasks_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('event_tasks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('event_id')->constrained('events')->cascadeOnDelete();
            $table->foreignUuid('group_id')->nullable()->constrained('event_staffing_groups')->nullOnDelete();
            $table->uuid('assigned_to')->nullable();
            $table->json('title');
            $table->json('description')->nullable();
            $table->string('status');
            $table->timestamp('due_at')->nullable();
            $table->uuid('created_by');
            $table->timestamps();

            $table->foreign('assigned_to')->references('id')->on('users')->nullOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnDelete();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_tasks');
    }
};
