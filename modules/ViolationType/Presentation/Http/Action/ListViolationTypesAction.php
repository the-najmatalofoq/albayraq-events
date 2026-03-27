<?php
// modules/ViolationType/Presentation/Http/Action/ListViolationTypesAction.php
declare(strict_types=1);

namespace Modules\ViolationType\Presentation\Http\Action;

use Modules\ViolationType\Domain\Repository\ViolationTypeRepositoryInterface;
use Modules\ViolationType\Presentation\Http\Presenter\ViolationTypePresenter;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class ListViolationTypesAction
{
    public function __construct(
        private ViolationTypeRepositoryInterface $repository,
        private JsonResponder $responder
    ) {}

    public function __invoke(): mixed
    {
        $types = $this->repository->listAll();
        
        return $this->responder->success(
            data: array_map(fn($type) => ViolationTypePresenter::fromDomain($type), $types)
        );
    }
}
