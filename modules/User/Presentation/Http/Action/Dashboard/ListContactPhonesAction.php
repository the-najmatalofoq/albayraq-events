<?php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\Shared\Presentation\Http\Request\BaseFilterRequest;
use Modules\Shared\Domain\ValueObject\PaginationCriteria;
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
        $filters = $request->toFilterCriteria()->toArray();
        $perPage = $request->getPerPage();

        $paginator = $this->contactRepository->paginate($perPage, $filters);

        return $this->responder->paginated(
            $paginator->items(),
            $paginator->total(),
            new PaginationCriteria(
                $paginator->currentPage(),
                $paginator->perPage()
            ),
            fn($contact) => ContactPhonePresenter::fromDomain($contact)
        );

    }
}
