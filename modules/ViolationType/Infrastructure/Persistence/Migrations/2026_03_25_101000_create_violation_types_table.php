<?php
// modules/ViolationType/Infrastructure/Persistence/Migrations/2026_03_25_101000_create_violation_types_table.php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('violation_types', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->json('name');
            $table->decimal('default_deduction', 10, 2)->nullable();
            $table->string('severity');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('violation_types');
    }
};
