<?php
// filePath: modules/Question/Infrastructure/Persistence/Seeders/QuestionSeeder.php
declare(strict_types=1);

namespace Modules\Question\Infrastructure\Persistence\Seeders;

use Illuminate\Database\Seeder;
use Modules\Question\Infrastructure\Persistence\Eloquent\QuestionModel;

final class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        QuestionModel::factory()->count(50)->create();
    }
}
