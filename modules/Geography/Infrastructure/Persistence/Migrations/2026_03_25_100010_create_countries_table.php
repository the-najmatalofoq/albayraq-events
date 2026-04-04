<?php
// modules/Geography/Infrastructure/Persistence/Migrations/2026_03_25_100010_create_countries_table.php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->char('code', 2)->unique();
            $table->json('name')->nullable();
            $table->string('phone_code')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
