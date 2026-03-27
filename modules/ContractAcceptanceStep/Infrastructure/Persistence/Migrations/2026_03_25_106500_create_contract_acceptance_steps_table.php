<?php
// modules/ContractAcceptanceStep/Infrastructure/Persistence/Migrations/2026_03_25_106500_create_contract_acceptance_steps_table.php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contract_acceptance_steps', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('contract_id')->constrained('event_contracts')->cascadeOnDelete();
            $table->string('step');
            $table->timestamp('completed_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->unique(['contract_id', 'step']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contract_acceptance_steps');
    }
};
