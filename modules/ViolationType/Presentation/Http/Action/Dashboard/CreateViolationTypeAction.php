<?php

declare(strict_types=1);

namespace Modules\ViolationType\Presentation\Http\Action\Dashboard;

use Modules\ViolationType\Domain\Repository\ViolationTypeRepositoryInterface;
use Modules\ViolationType\Domain\ViolationType;
use Modules\ViolationType\Domain\Enum\ViolationSeverityEnum;
use Modules\ViolationType\Presentation\Http\Presenter\ViolationTypePresenter;
use Modules\ViolationType\Presentation\Http\Request\Dashboard\StoreViolationTypeRequest;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\Shared\Domain\ValueObject\Money;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class CreateViolationTypeAction
{
    public function __construct(
        private ViolationTypeRepositoryInterface $repository,
        private JsonResponder $responder
    ) {}

    public function __invoke(StoreViolationTypeRequest $request): mixed
    {
        $id = $this->repository->nextIdentity();
        
        $deduction = null;
        if ($request->has('default_deduction_amount')) {
            $deduction = new Money(
                (float)$request->validated('default_deduction_amount'),
                $request->validated('default_deduction_currency')
            );
        }

        $type = ViolationType::create(
            uuid: $id,
            name: TranslatableText::fromArray($request->validated('name')),
            defaultDeduction: $deduction,
            severity: ViolationSeverityEnum::from($request->validated('severity')),
            isActive: (bool)$request->validated('is_active', true)
        );

        $this->repository->save($type);

        return $this->responder->created(
            ViolationTypePresenter::fromDomain($type),
            'messages.violation_type_created'
        );
    }
}
