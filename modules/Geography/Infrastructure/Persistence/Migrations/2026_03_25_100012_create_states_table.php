<?php
// modules/Geography/Infrastructure/Persistence/Migrations/2026_03_25_100012_create_states_table.php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('states', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('country_id')->constrained('countries')->cascadeOnDelete();
            $table->json('name')->nullable();
            $table->timestamps();

            $table->index(['country_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('states');
    }
};
