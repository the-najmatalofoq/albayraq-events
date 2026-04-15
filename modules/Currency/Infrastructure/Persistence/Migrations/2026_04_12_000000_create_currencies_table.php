<?php
// modules/Currency/Infrastructure/Persistence/Migrations/2026_04_12_000000_create_currencies_table.php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('currencies', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->json('name');
            $table->string('code', 3)->unique();
            $table->string('symbol', 10);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};
