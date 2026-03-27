<?php

declare(strict_types=1);

namespace Modules\Shared\Presentation\Http;

use Modules\Shared\Domain\Service\TranslatorInterface;
use Modules\Shared\Domain\Enum\ErrorCode;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

final readonly class JsonResponder
{
    public function __construct(
        private TranslatorInterface $translator
    ) {}

    public function success(
        mixed $data = null,
        int $status = 200,
        ?string $messageKey = null,
        array $messageReplace = []
    ): ResponseInterface {
        $message = $messageKey
            ? $this->translator->trans($messageKey, $messageReplace)
            : 'OK';

        return $this->respond([
            'message'    => $message,
            'statusCode' => $status,
            'errorCode'  => null,
            'timestamp'  => now()->toIso8601String(),
            'data'       => $data,
            'errors'     => null,
        ], $status);
    }

    public function error(
        string $errorCode,
        int $status,
        ?string $messageKey = null,
        array $messageReplace = [],
        mixed $errors = null
    ): ResponseInterface {
        $message = $messageKey
            ? $this->translator->trans($messageKey, $messageReplace)
            : 'Error';

        return $this->respond([
            'message'    => $message,
            'statusCode' => $status,
            'errorCode'  => $errorCode,
            'timestamp'  => now()->toIso8601String(),
            'data'       => null,
            'errors'     => $errors,
        ], $status);
    }

    public function ok(mixed $data): ResponseInterface
    {
        return $this->success($data, 200);
    }

    public function created(mixed $data): ResponseInterface
    {
        return $this->success($data, 201);
    }

    public function noContent(): ResponseInterface
    {
        return new Response(204);
    }

    public function validationError(array $errors, string $messageKey = 'messages.errors.validation_failed'): ResponseInterface
    {
        return $this->error(
            errorCode: ErrorCode::VALIDATION_FAILED->value,
            status: 422,
            messageKey: $messageKey,
            errors: $errors
        );
    }

    private function respond(array $data, int $status): ResponseInterface
    {
        return new Response(
            status: $status,
            headers: ['Content-Type' => 'application/json'],
            body: json_encode($data, JSON_THROW_ON_ERROR),
        );
    }
}

