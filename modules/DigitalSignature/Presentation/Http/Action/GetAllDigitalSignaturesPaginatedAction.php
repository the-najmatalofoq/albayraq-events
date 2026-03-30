<?php
declare(strict_types=1);

namespace Modules\DigitalSignature\Presentation\Http\Action;

use Modules\DigitalSignature\Application\Query\GetAllPaginated\GetAllDigitalSignaturesPaginatedQuery;
use Modules\DigitalSignature\Application\Query\GetAllPaginated\GetAllDigitalSignaturesPaginatedHandler;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\Shared\Presentation\Http\Presenter\DigitalSignaturePresenter;
use Modules\Shared\Presentation\Http\Presenter\PaginatedDataPresenter;
use Modules\Shared\Domain\ValueObject\FilterCriteria;
use Modules\Shared\Domain\ValueObject\PaginationCriteria;
use Modules\Shared\Domain\ValueObject\SortCriteria;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final readonly class GetAllDigitalSignaturesPaginatedAction
{
    public function __construct(
        private GetAllDigitalSignaturesPaginatedHandler $handler,
        private JsonResponder $responder,
    ) {}

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();

        $pagination = PaginationCriteria::fromArray($queryParams);
        $filters = FilterCriteria::fromArray([
            'contract_id' => $queryParams['contract_id'] ?? null,
            'from_date' => $queryParams['from_date'] ?? null,
            'to_date' => $queryParams['to_date'] ?? null,
        ]);
        $sort = SortCriteria::fromArray($queryParams, 'signed_at', 'desc');

        $result = $this->handler->handle(
            new GetAllDigitalSignaturesPaginatedQuery($pagination, $filters, $sort)
        );

        $presentedData = PaginatedDataPresenter::present(
            data: $result['data'],
            total: $result['total'],
            pagination: $pagination,
            presenter: [DigitalSignaturePresenter::class, 'toSummaryArray']
        );

        return $this->responder->ok($presentedData);
    }
}
