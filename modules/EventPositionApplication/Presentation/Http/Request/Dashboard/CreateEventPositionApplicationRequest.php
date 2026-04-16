<?php
// filePath: modules/EventPositionApplication/Presentation/Http/Request/Crm/CrmCreateEventPositionApplicationRequest.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Presentation\Http\Request\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

final class CreateEventPositionApplicationRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'position_id' => ['required', 'exists:event_staffing_positions,id'],
            'status' => ['sometimes', 'string', 'in:pending,approved,rejected,cancelled'],
            'ranking_score' => ['sometimes', 'numeric', 'min:0', 'max:999.99'],
        ];
    }
}
