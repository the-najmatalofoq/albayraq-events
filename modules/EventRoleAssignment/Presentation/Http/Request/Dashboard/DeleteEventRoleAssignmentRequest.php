<?php
// modules/EventRoleAssignment/Presentation/Http/Request/Crm/CrmDeleteEventRoleAssignmentRequest.php
declare(strict_types=1);

namespace Modules\EventRoleAssignment\Presentation\Http\Request\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

final class DeleteEventRoleAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }
}
