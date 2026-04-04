<?php
// modules/Geography/Infrastructure/Persistence/Migrations/2026_03_25_100013_create_cities_table.php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('country_id')->constrained('countries')->cascadeOnDelete();
            $table->foreignUuid('state_id')->nullable()->constrained('states')->cascadeOnDelete();
            $table->json('name')->nullable();
            $table->timestamps();

            $table->index(['country_id', 'state_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
