<?php
// filePath: modules/Question/Infrastructure/Persistence/Eloquent/Factories/QuestionFactory.php
declare(strict_types=1);

namespace Modules\Question\Infrastructure\Persistence\Eloquent\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Question\Infrastructure\Persistence\Eloquent\QuestionModel;
use Modules\Quiz\Infrastructure\Persistence\Eloquent\QuizModel;

final class QuestionFactory extends Factory
{
    protected $model = QuestionModel::class;

    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'quiz_id' => QuizModel::factory(),
            'content' => [
                'en' => $this->faker->sentence() . '?',
                'ar' => 'هذا سؤال تجريبي رقم ' . $this->faker->randomNumber() . '؟',
            ],
            'type' => $this->faker->randomElement(['multiple_choice', 'true_false', 'open_ended']),
            'options' => [
                ['id' => '1', 'label' => ['en' => 'Option 1', 'ar' => 'الخيار 1'], 'is_correct' => true],
                ['id' => '2', 'label' => ['en' => 'Option 2', 'ar' => 'الخيار 2'], 'is_correct' => false],
            ],
            'score_weight' => $this->faker->numberBetween(1, 10),
        ];
    }
}
