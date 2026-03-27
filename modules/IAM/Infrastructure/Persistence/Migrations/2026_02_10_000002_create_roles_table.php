<?php
// modules/IAM/Infrastructure/Persistence/Migrations/2026_02_10_000002_create_roles_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug')->unique();              // e.g. 'general_manager'
            $table->json('name');                          // {ar: "...", en: "..."}
            $table->boolean('is_global')->default(false);
            $table->tinyInteger('level')->unsigned();      // 0=System ... 7=Individual
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
