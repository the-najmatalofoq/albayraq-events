<?php
// modules/ParticipationViolation/Infrastructure/Persistence/Migrations/2026_03_25_108000_create_participation_violations_table.php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('participation_violations', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('event_participation_id')->constrained('event_participations')->cascadeOnDelete();
            $table->foreignUuid('violation_type_id')->constrained('violation_types')->restrictOnDelete();
            $table->foreignUuid('reported_by')->constrained('users')->restrictOnDelete();
            $table->text('description')->nullable();
            $table->date('date');
            $table->unsignedTinyInteger('current_tier')->default(1);
            $table->string('status')->default('pending');
            $table->decimal('deduction_amount', 10, 2)->nullable();
            $table->foreignUuid('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participation_violations');
    }
};
