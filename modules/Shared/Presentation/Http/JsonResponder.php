<?php
// modules/Shared/Presentation/Http/JsonResponder.php
declare(strict_types=1);

namespace Modules\Shared\Presentation\Http;

use Illuminate\Http\JsonResponse;
use Modules\Shared\Domain\Service\TranslatorInterface;
use Modules\Shared\Domain\Enum\ErrorCodeEnum;
use Modules\Shared\Presentation\Http\Presenter\PaginatedDataPresenter;
use Modules\Shared\Domain\ValueObject\PaginationCriteria;
use Carbon\Carbon;

final readonly class JsonResponder
{
    public function __construct(
        private TranslatorInterface $translator
    ) {
    }

    public function paginated(
        array $items,
        int $total,
        PaginationCriteria $pagination,
        ?callable $presenter = null,
        int $status = 200,
        ?string $messageKey = null
    ): JsonResponse {
        $presented = PaginatedDataPresenter::present(
            data: $items,
            total: $total,
            pagination: $pagination,
            presenter: $presenter ?? fn($item) => $item
        );

        return $this->success($presented, $status, $messageKey);
    }

    public function success(mixed $data = null, int $status = 200, ?string $messageKey = null): JsonResponse
    {
        return new JsonResponse([
            'message' => $messageKey ? $this->translator->trans($messageKey) : 'OK',
            'statusCode' => $status,
            'errorCode' => null,
            'timestamp' => Carbon::now()->toIso8601String(),
            'data' => $data,
            'errors' => null,
        ], $status);
    }

    public function created(mixed $data = null, ?string $messageKey = 'messages.created'): JsonResponse
    {
        return $this->success($data, 201, $messageKey);
    }

    public function error(string $errorCode, int $status, ?string $messageKey = null, mixed $errors = null): JsonResponse
    {
        return new JsonResponse([
            'message' => $messageKey ? $this->translator->trans($messageKey) : 'Error',
            'statusCode' => $status,
            'errorCode' => $errorCode,
            'timestamp' => Carbon::now()->toIso8601String(),
            'data' => null,
            'errors' => $errors,
        ], $status);
    }

    public function validationError(array $errors): JsonResponse
    {
        return $this->error(
            errorCode: ErrorCodeEnum::VALIDATION_FAILED->value,
            status: 422,
            messageKey: __('messages.errors.validation_failed'),
            errors: $errors
        );
    }

    public function noContent(): JsonResponse
    {
        return new JsonResponse(status: 204);
    }

    public function unauthorized(?string $messageKey = 'auth.unauthorized'): JsonResponse
    {
        return $this->error(
            errorCode: ErrorCodeEnum::UNAUTHORIZED->value,
            status: 401,
            messageKey: $messageKey
        );
    }

    public function forbidden(?string $messageKey = 'auth.forbidden'): JsonResponse
    {
        return $this->error(
            errorCode: ErrorCodeEnum::FORBIDDEN->value,
            status: 403,
            messageKey: $messageKey
        );
    }

    public function notFound(?string $messageKey = 'messages.not_found'): JsonResponse
    {
        return $this->error(
            errorCode: ErrorCodeEnum::NOT_FOUND->value,
            status: 404,
            messageKey: $messageKey
        );
    }
}
