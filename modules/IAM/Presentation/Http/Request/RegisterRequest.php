<?php

declare(strict_types=1);

namespace Modules\IAM\Presentation\Http\Request;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Modules\Shared\Presentation\Validation\InputValidator;

final readonly class RegisterRequest
{
    public function __construct(
        private InputValidator $validator,
    ) {}

    public function validated(Request $request): array
    {
        return $this->validator->validate(
            (array) $request->all(),
            array_merge(
                $this->userRules(),
                $this->profileRules(),
                $this->bankRules(),
                $this->contactRules(),
                $this->attachmentRules()
            )
        );
    }

    private function userRules(): array
    {
    return [
            'user' => ['required', 'array'],
            'user.name' => ['required', 'json'],
            'user.email' => ['nullable', 'email', 'max:255'],
            'user.phone' => ['required', 'string', 'regex:/^(?:\+966|966|0)?5\d{8}$/', 'unique:users,phone'],
            'user.password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8)->uncompromised()->mixedCase()->numbers()->symbols()
            ],
            'user.avatar' => ['nullable', 'image', 'max:1024', 'mimes:jpg,jpeg,png'],
        ];
    }

    private function profileRules(): array
    {
        return [
            'profile' => ['required', 'array'],
            'profile.full_name' => ['required', 'json'],
            'profile.birth_date' => ['required', 'date', 'before:today'],
            'profile.nationality' => ['required', 'string', 'max:255'],
            'profile.gender' => ['required'],
            'profile.height' => ['required', 'numeric', 'min:50', 'max:250'],
            'profile.weight' => ['required', 'numeric', 'min:20', 'max:300'],
        ];
    }

    private function bankRules(): array
    {
        return [
            'bank' => ['required', 'array'],
            'bank.account_owner' => ['required', 'string', 'max:255'],
            'bank.bank_name' => ['required', 'string', 'max:255'],
            'bank.iban' => ['required', 'string', 'unique:bank_details,iban'],
        ];
    }

    private function contactRules(): array
    {
        return [
            'contact_phones' => ['required', 'array'],
            'contact_phones.name' => ['required', 'string', 'max:255'],
            'contact_phones.phone' => ['required', 'string', 'regex:/^(?:\+966|966|0)?5\d{8}$/'],
            'contact_phones.relation' => ['nullable', 'string', 'max:255'],
        ];
    }

    private function attachmentRules(): array
    {
        return [
            'attachments' => ['required', 'array'],
            'attachments.medical_record' => ['nullable', 'file', 'max:2048', 'mimes:pdf,png,jpg'],
            'attachments.identity_personal' => ['required', 'file', 'max:2048', 'mimes:pdf'],
            'attachments.cv' => ['required', 'file', 'max:2048', 'mimes:pdf'],
        ];
    }
}
