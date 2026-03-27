<?php
// modules/ViolationType/Infrastructure/Persistence/Migrations/2026_03_26_000001_create_violation_types_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('violation_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->json('name');
            $table->decimal('default_deduction_amount', 12, 2)->nullable();
            $table->string('default_deduction_currency', 3)->default('SAR');
            $table->string('severity'); // low, medium, high
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('violation_types');
    }
};
