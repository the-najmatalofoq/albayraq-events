<?php
// filePath: modules/Question/Presentation/Http/Request/Crm/CrmCreateQuestionRequest.php
declare(strict_types=1);

namespace Modules\Question\Presentation\Http\Request\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

final class CreateQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'quiz_id' => ['required', 'exists:quizzes,id'],
            'content' => ['required', 'array'],
            'content.en' => ['required', 'string'],
            'content.ar' => ['required', 'string'],
            // fix: we must make enum for the types
            'type' => ['required', 'string', 'in:multiple_choice,true_false,open_ended'],
            'options' => ['required_if:type,multiple_choice', 'array'],
            'score_weight' => ['required', 'integer', 'min:1'],
        ];
    }
}
