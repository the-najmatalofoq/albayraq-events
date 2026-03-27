<?php
// modules/User/Infrastructure/Persistence/Migrations/2026_03_26_000005_create_user_profiles_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->unique();
            $table->string('employee_number')->unique();
            $table->json('job_title');
            $table->json('department');
            $table->date('hiring_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
