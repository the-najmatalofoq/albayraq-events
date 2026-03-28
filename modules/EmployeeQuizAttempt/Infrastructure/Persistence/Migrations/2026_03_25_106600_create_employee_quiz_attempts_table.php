<?php
// modules/EmployeeQuizAttempt/Infrastructure/Persistence/Migrations/2026_03_25_106600_create_employee_quiz_attempts_table.php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_quiz_attempts', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('quiz_id')->constrained('quizzes')->cascadeOnDelete();
            $table->foreignUuid('event_participation_id')->constrained('event_participations')->cascadeOnDelete();
            $table->unsignedSmallInteger('score');
            $table->string('status')->default('pending'); // passed, failed, pending
            $table->timestamp('started_at');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_quiz_attempts');
    }
};
