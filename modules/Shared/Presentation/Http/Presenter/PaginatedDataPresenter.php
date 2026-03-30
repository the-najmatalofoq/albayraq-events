<?php
declare(strict_types=1);

namespace Modules\Shared\Presentation\Http\Presenter;

use Modules\Shared\Domain\ValueObject\PaginationCriteria;

final class PaginatedDataPresenter
{
    /**
     * @param array<object> $data
     * @param callable $presenter Function that transforms a domain object to array
     */
    public static function present(
        array $data,
        int $total,
        PaginationCriteria $pagination,
        callable $presenter
    ): array {
        return [
            'data' => array_map($presenter, $data),
            'meta' => [
                'total' => $total,
                'page' => $pagination->page,
                'per_page' => $pagination->perPage,
                'last_page' => (int) ceil($total / $pagination->perPage),
            ],
        ];
    }
}
