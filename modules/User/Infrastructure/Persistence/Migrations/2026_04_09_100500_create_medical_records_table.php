<?php
// modules/User/Infrastructure/Persistence/Migrations/2026_04_09_100500_create_medical_records_table.php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_records', function (Blueprint $blueprint) {
            $blueprint->uuid('id')->primary();
            $blueprint->uuid('user_id')->unique();
            $blueprint->string('blood_type');
            $blueprint->text('chronic_diseases')->nullable();
            $blueprint->text('allergies')->nullable();
            $blueprint->text('medications')->nullable();
            $blueprint->timestamps();

            $blueprint->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};
