<?php

declare(strict_types=1);

namespace Modules\ViolationType\Presentation\Http\Action\Dashboard;

use Modules\ViolationType\Domain\Repository\ViolationTypeRepositoryInterface;
use Modules\ViolationType\Domain\ValueObject\ViolationTypeId;
use Modules\ViolationType\Domain\Enum\ViolationSeverityEnum;
use Modules\ViolationType\Presentation\Http\Presenter\ViolationTypePresenter;
use Modules\ViolationType\Presentation\Http\Request\Dashboard\UpdateViolationTypeRequest;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\Shared\Domain\ValueObject\Money;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class UpdateViolationTypeAction
{
    public function __construct(
        private ViolationTypeRepositoryInterface $repository,
        private JsonResponder $responder
    ) {}

    public function __invoke(UpdateViolationTypeRequest $request, string $id): mixed
    {
        $typeId = ViolationTypeId::fromString($id);
        $type = $this->repository->findById($typeId);

        if (!$type) {
            return $this->responder->notFound('messages.violation_type_not_found');
        }

        $name = $request->has('name') ? TranslatableText::fromArray($request->validated('name')) : $type->name;
        
        $deduction = $type->defaultDeduction;
        if ($request->has('default_deduction_amount')) {
            $deduction = new Money(
                (float)$request->validated('default_deduction_amount'),
                $request->validated('default_deduction_currency', $deduction?->currency ?? 'SAR')
            );
        }

        $severity = $request->has('severity') 
            ? ViolationSeverityEnum::from($request->validated('severity')) 
            : $type->severity;

        $type->update($name, $deduction, $severity);

        if ($request->has('is_active')) {
            $request->validated('is_active') ? $type->activate() : $type->deactivate();
        }

        $this->repository->save($type);

        return $this->responder->success(
            data: ViolationTypePresenter::fromDomain($type),
            status: 200,
            messageKey: 'messages.violation_type_updated'
        );
    }
}
