<?php
// modules/EventAttendance/Infrastructure/Persistence/Migrations/2026_03_26_000016_create_event_attendance_records_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('event_attendance_records', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('event_participation_id')->constrained('event_participations')->cascadeOnDelete();
            $table->date('date');
            $table->timestamp('check_in_at')->nullable();
            $table->timestamp('check_out_at')->nullable();
            $table->decimal('check_in_latitude', 10, 7)->nullable();
            $table->decimal('check_in_longitude', 10, 7)->nullable();
            $table->decimal('check_out_latitude', 10, 7)->nullable();
            $table->decimal('check_out_longitude', 10, 7)->nullable();
            $table->string('method');
            $table->uuid('verified_by')->nullable();
            $table->timestamps();

            $table->foreign('verified_by')->references('id')->on('users')->nullOnDelete();

            $table->index(['event_participation_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_attendance_records');
    }
};
