<?php
// modules/PenaltyType/Application/Command/CreatePenaltyType/CreatePenaltyTypeHandler.php
declare(strict_types=1);

namespace Modules\PenaltyType\Application\Command\CreatePenaltyType;

use Modules\PenaltyType\Domain\Repository\PenaltyTypeRepositoryInterface;
use Modules\PenaltyType\Domain\PenaltyType;
use Modules\PenaltyType\Domain\ValueObject\PenaltyTypeId;

final readonly class CreatePenaltyTypeHandler
{
    public function __construct(
        private PenaltyTypeRepositoryInterface $repository
    ) {}

    public function handle(CreatePenaltyTypeCommand $command): PenaltyTypeId
    {
        $id = $this->repository->nextIdentity();

        $penaltyType = PenaltyType::create(
            uuid: $id,
            slug: $command->slug,
            name: $command->name,
            isActive: $command->isActive
        );

        $this->repository->save($penaltyType);

        return $id;
    }
}
