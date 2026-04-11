<?php
// filePath: modules/Question/Infrastructure/Persistence/Migrations/2026_04_11_120000_fix_and_refactor_questions_table.php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // First drop existing table to ensure clean wave migration with proper constraints
        Schema::dropIfExists('questions');

        Schema::create('questions', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('quiz_id')->constrained('quizzes')->cascadeOnDelete();
            $table->json('content');
            $table->string('type')->default('multiple_choice');
            $table->json('options'); // [{ "id": "A", "label": { "en": "...", "ar": "..." }, "is_correct": true }]
            $table->unsignedSmallInteger('score_weight')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
