<?php
// modules/ViolationType/Application/Command/DeleteViolationType/DeleteViolationTypeHandler.php
declare(strict_types=1);

namespace Modules\ViolationType\Application\Command\DeleteViolationType;

use Modules\ViolationType\Domain\Repository\ViolationTypeRepositoryInterface;
use Modules\ViolationType\Domain\ValueObject\ViolationTypeId;

final readonly class DeleteViolationTypeHandler
{
    public function __construct(
        private ViolationTypeRepositoryInterface $repository
    ) {}

    public function handle(DeleteViolationTypeCommand $command): void
    {
        $id = ViolationTypeId::fromString($command->id);
        $this->repository->delete($id);
    }
}
