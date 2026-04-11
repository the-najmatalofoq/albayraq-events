<?php
// modules/EventRoleAssignment/Presentation/Http/Request/Crm/CrmListEventRoleAssignmentsRequest.php
declare(strict_types=1);

namespace Modules\EventRoleAssignment\Presentation\Http\Request\Crm;

use Illuminate\Foundation\Http\FormRequest;

final class CrmListEventRoleAssignmentsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'event_id' => ['nullable', 'exists:events,id'],
        ];
    }

    public function eventId(): ?string
    {
        return $this->query('event_id');
    }
}
