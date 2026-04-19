<?php
// modules/EventBreakRequest/Infrastructure/Persistence/Migrations/2026_04_13_135100_create_break_requests_table.php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('break_requests', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('event_participation_id')->constrained('event_participations')->cascadeOnDelete();
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            // Using smallInteger because duration will typically be <= 60
            $table->smallInteger('duration_minutes');
            $table->string('status')->default('pending'); // pending, approved, rejected, cancelled
            $table->foreignUuid('requested_by')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->foreignUuid('cover_employee_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['event_participation_id', 'date', 'status']);
            $table->unique(['event_participation_id', 'date', 'start_time', 'end_time'], 'uq_b_req_part_date_st_et');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('break_requests');
    }
};
