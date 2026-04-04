<?php
// modules/Geography/Infrastructure/Persistence/Migrations/2026_03_25_100110_create_employee_nationalities_table.php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_nationalities', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('employee_profile_id')->constrained('employee_profiles')->cascadeOnDelete();
            $table->foreignUuid('nationality_id')->constrained('nationalities')->cascadeOnDelete();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();

            $table->unique(['employee_profile_id', 'nationality_id'], 'emp_nat_unique');
            $table->index(['employee_profile_id', 'is_primary'], 'emp_profile_primary_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_nationalities');
    }
};
