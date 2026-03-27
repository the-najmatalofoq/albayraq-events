<?php
// modules/ContractRejectionReason/Infrastructure/Persistence/Migrations/2026_03_26_000002_create_contract_rejection_reasons_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contract_rejection_reasons', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->json('reason');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contract_rejection_reasons');
    }
};
