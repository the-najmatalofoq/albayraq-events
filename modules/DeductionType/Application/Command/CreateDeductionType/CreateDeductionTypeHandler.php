<?php
// modules/DeductionType/Application/Command/CreateDeductionType/CreateDeductionTypeHandler.php
declare(strict_types=1);

namespace Modules\DeductionType\Application\Command\CreateDeductionType;

use Modules\DeductionType\Domain\Repository\DeductionTypeRepositoryInterface;
use Modules\DeductionType\Domain\DeductionType;
use Modules\DeductionType\Domain\ValueObject\DeductionTypeId;

final readonly class CreateDeductionTypeHandler
{
    public function __construct(
        private DeductionTypeRepositoryInterface $repository
    ) {}

    public function handle(CreateDeductionTypeCommand $command): DeductionTypeId
    {
        $id = $this->repository->nextIdentity();

        $deductionType = DeductionType::create(
            uuid: $id,
            slug: $command->slug,
            name: $command->name,
            isActive: $command->isActive
        );

        $this->repository->save($deductionType);

        return $id;
    }
}
