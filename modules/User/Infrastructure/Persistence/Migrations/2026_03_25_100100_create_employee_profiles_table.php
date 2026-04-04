<?php
// modules/User/Infrastructure/Persistence/Migrations/2026_03_25_100100_create_employee_profiles_table.php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_profiles', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->date('birth_date')->nullable();
            $table->foreignUuid('city_id')->nullable()->constrained('cities');
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->float('height')->nullable();
            $table->float('weight')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_profiles');
    }
};
