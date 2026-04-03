<?php
// modules/Shared/Infrastructure/Persistence/Migrations/2026_03_25_102600_create_work_schedules_table.php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_schedules', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('schedulable_id');
            $table->string('schedulable_type');
            $table->json('days_of_week'); // [1, 2, 3, 4, 5]
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['schedulable_id', 'schedulable_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_schedules');
    }
};
