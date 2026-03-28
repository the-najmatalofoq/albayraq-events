<?php
// modules/Question/Infrastructure/Persistence/Migrations/2026_03_25_104100_create_questions_table.php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('quiz_id')->constrained('quizzes')->cascadeOnDelete();
            $table->json('content');
            $table->string('type')->default('multiple_choice');
            $table->json('options'); // [{ "id": "A", "label": { "en": "...", "ar": "..." }, "is_correct": true }]
            $table->unsignedSmallInteger('score_weight')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
