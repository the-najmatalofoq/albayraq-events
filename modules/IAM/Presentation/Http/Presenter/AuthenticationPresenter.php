<?php

declare(strict_types=1);

namespace Modules\IAM\Presentation\Http\Presenter;

final class AuthenticationPresenter
{
    public static function fromResult(array $result): array
    {
        return [
            'access_token'  => $result['tokens']['access_token'] ?? null,
            'refresh_token' => $result['tokens']['refresh_token'] ?? null,
            'expires_in'    => $result['tokens']['expires_in'] ?? null,
            'user_id'       => $result['user']->uuid->value,
        ];
    }
}
