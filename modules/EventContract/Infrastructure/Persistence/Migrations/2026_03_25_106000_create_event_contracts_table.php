<?php
// modules/EventContract/Infrastructure/Persistence/Migrations/2026_03_25_106000_create_event_contracts_table.php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_contracts', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('event_participation_id')->unique()->constrained('event_participations')->cascadeOnDelete();
            $table->string('contract_type');
            $table->decimal('wage_amount', 10, 2);
            $table->json('terms')->nullable();
            $table->string('status')->default('pending');
            $table->foreignUuid('rejection_reason_id')->nullable()->constrained('contract_rejection_reasons')->nullOnDelete();
            $table->text('rejection_notes')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_contracts');
    }
};
