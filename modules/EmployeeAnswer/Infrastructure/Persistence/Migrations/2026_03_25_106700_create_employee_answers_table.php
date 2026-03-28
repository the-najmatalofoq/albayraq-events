<?php
// modules/EmployeeAnswer/Infrastructure/Persistence/Migrations/2026_03_25_106700_create_employee_answers_table.php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_answers', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('attempt_id')->constrained('employee_quiz_attempts')->cascadeOnDelete();
            $table->foreignUuid('question_id')->constrained('questions')->cascadeOnDelete();
            $table->string('answer_id');
            $table->boolean('is_correct');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_answers');
    }
};
