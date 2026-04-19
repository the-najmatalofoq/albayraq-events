<?php
// modules/ViolationType/Application/Command/CreateViolationType/CreateViolationTypeHandler.php
declare(strict_types=1);

namespace Modules\ViolationType\Application\Command\CreateViolationType;

use Modules\ViolationType\Domain\Repository\ViolationTypeRepositoryInterface;
use Modules\ViolationType\Domain\ViolationType;
use Modules\ViolationType\Domain\ValueObject\ViolationTypeId;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\ViolationType\Domain\Enum\ViolationSeverityEnum;

final readonly class CreateViolationTypeHandler
{
    public function __construct(
        private ViolationTypeRepositoryInterface $repository
    ) {}

    public function handle(CreateViolationTypeCommand $command): ViolationTypeId
    {
        $id = $this->repository->nextIdentity();

        $violationType = ViolationType::create(
            uuid: $id,
            slug: $command->slug,
            name: $command->name,
            isActive: $command->isActive
        );

        $this->repository->save($violationType);

        return $id;
    }
}
