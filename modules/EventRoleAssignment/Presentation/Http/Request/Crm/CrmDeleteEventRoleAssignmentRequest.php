<?php
// modules/EventRoleAssignment/Presentation/Http/Request/Crm/CrmDeleteEventRoleAssignmentRequest.php
declare(strict_types=1);

namespace Modules\EventRoleAssignment\Presentation\Http\Request\Crm;

use Illuminate\Foundation\Http\FormRequest;

final class CrmDeleteEventRoleAssignmentRequest extends FormRequest
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
