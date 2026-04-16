<?php
// filePath: modules/Question/Presentation/Http/Request/Crm/CrmListQuestionsRequest.php
declare(strict_types=1);

namespace Modules\Question\Presentation\Http\Request\Dashboard;

use Modules\Shared\Presentation\Http\Request\BaseFilterRequest;

final class ListQuestionsRequest extends BaseFilterRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'quiz_id' => ['sometimes', 'exists:quizzes,id'],
            // fix: use the Enum
            'type' => ['sometimes', 'string', 'in:multiple_choice,true_false,open_ended'],
            'trashed' => ['sometimes', 'string', 'in:with,only'],
        ]);
    }
}
