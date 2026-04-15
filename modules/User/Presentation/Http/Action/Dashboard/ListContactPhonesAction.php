<?php

declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\Shared\Presentation\Http\Request\BaseFilterRequest;
use Modules\User\Domain\Repository\ContactPhoneRepositoryInterface;
use Modules\User\Presentation\Http\Presenter\ContactPhonePresenter;

final readonly class ListContactPhonesAction 
{
    public function __construct(
        private ContactPhoneRepositoryInterface $contactRepository,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(BaseFilterRequest $request): JsonResponse
    {
        $criteria = $request->toFilterCriteria();
        $perPage = $request->getPerPage();

        $paginator = $this->contactRepository->paginate($criteria, $perPage);

        return $this->responder->paginated(
            items: $paginator->items(),
            total: $paginator->total(),
            pagination: $request->toPaginationCriteria(),
            presenter: fn($contact) => ContactPhonePresenter::fromDomain($contact)
        );
    }
}
