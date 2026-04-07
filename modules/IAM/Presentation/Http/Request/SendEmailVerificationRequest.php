<?php
declare(strict_types=1);

namespace Modules\IAM\Presentation\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

final class SendEmailVerificationRequest extends FormRequest
{
    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [];
    }
}
