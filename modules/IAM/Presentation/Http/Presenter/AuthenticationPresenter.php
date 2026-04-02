<?php

declare(strict_types=1);

namespace Modules\IAM\Presentation\Http\Presenter;

use Modules\User\Presentation\Http\Presenter\UserPresenter;

/**
 * Handles the HTTP representation of the login action response.
 */
final class AuthenticationPresenter
{
    public static function fromResult(array $result): array
    {
        return [
            'access_token'  => $result['tokens']['access_token'] ?? null,
            'refresh_token' => $result['tokens']['refresh_token'] ?? null,
            'expires_in'    => $result['tokens']['expires_in'] ?? null,
            'user'          => UserPresenter::fromDomain($result['user']),
        ];
    }
}
