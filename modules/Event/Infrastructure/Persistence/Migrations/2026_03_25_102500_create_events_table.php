<?php
// modules/Event/Infrastructure/Persistence/Migrations/2026_03_25_102500_create_events_table.php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->json('name');
            $table->json('description')->nullable();
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->unsignedInteger('geofence_radius');
            $table->json('address')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->time('daily_start_time');
            $table->time('daily_end_time');
            $table->json('employment_terms')->nullable();
            $table->string('status')->default('draft');
            $table->foreignUuid('created_by')->constrained('users')->restrictOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
