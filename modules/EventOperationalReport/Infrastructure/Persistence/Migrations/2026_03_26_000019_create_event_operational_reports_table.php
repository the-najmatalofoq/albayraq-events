<?php
// modules/EventOperationalReport/Infrastructure/Persistence/Migrations/2026_03_26_000019_create_event_operational_reports_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('event_operational_reports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('event_id')->constrained('events')->cascadeOnDelete();
            $table->string('report_type_id'); // e.g. DAILY, FINAL
            $table->json('content');
            $table->uuid('reported_by');
            $table->string('status');
            $table->timestamps();

            $table->foreign('reported_by')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_operational_reports');
    }
};
