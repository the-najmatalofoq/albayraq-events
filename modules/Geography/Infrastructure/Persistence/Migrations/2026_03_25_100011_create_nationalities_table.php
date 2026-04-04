<?php
// modules/Geography/Infrastructure/Persistence/Migrations/2026_03_25_100011_create_nationalities_table.php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nationalities', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('country_id')->constrained('countries')->cascadeOnDelete();
            $table->json('name')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['country_id']);
            $table->index(['country_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nationalities');
    }
};
