<?php
// modules/Shared/Infrastructure/Persistence/Migrations/2026_03_25_103050_create_wages_table.php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wages', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('wageable_id');
            $table->string('wageable_type');
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('SAR');
            $table->string('period')->default('hourly'); // hourly, daily, monthly
            $table->timestamps();

            $table->index(['wageable_id', 'wageable_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wages');
    }
};
