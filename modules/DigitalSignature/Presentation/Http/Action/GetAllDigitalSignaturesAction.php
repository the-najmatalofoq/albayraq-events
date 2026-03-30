<?php
declare(strict_types=1);

namespace Modules\DigitalSignature\Presentation\Http\Action;

use Modules\DigitalSignature\Application\Query\GetAll\GetAllDigitalSignaturesQuery;
use Modules\DigitalSignature\Application\Query\GetAll\GetAllDigitalSignaturesHandler;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\Shared\Presentation\Http\Presenter\DigitalSignaturePresenter;
use Modules\Shared\Domain\ValueObject\FilterCriteria;
use Modules\Shared\Domain\ValueObject\SortCriteria;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final readonly class GetAllDigitalSignaturesAction
{
    public function __construct(
        private GetAllDigitalSignaturesHandler $handler,
        private JsonResponder $responder,
    ) {}

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();

        $filters = FilterCriteria::fromArray([
            'contract_id' => $queryParams['contract_id'] ?? null,
            'from_date' => $queryParams['from_date'] ?? null,
            'to_date' => $queryParams['to_date'] ?? null,
        ]);
        $sort = SortCriteria::fromArray($queryParams, 'signed_at', 'desc');

        $signatures = $this->handler->handle(new GetAllDigitalSignaturesQuery($filters, $sort));

        $data = array_map(
            [DigitalSignaturePresenter::class, 'toSummaryArray'],
            $signatures
        );

        return $this->responder->ok($data);
    }
}
