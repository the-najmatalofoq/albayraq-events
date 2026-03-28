<?php
// modules/Role/Infrastructure/Persistence/Migrations/2026_03_25_100500_create_roles_table.php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('slug')->unique(); // e.g. 'general_manager'
            $table->json('name'); // {ar: "...", en: "..."}
            $table->boolean('is_global')->default(false);
            $table->string('level');   // system, executive, project, etc.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
