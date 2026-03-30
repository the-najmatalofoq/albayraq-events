<?php
declare(strict_types=1);

namespace Modules\DigitalSignature\Application\Command\Delete;

use Modules\DigitalSignature\Domain\Repository\DigitalSignatureRepositoryInterface;
use Modules\DigitalSignature\Domain\ValueObject\DigitalSignatureId;
use Modules\Shared\Domain\Exception\NotFoundException;

final readonly class DeleteDigitalSignatureHandler
{
    public function __construct(
        private DigitalSignatureRepositoryInterface $repository,
    ) {}

    public function handle(DeleteDigitalSignatureCommand $command): void
    {
        $id = DigitalSignatureId::fromString($command->id);

        $signature = $this->repository->findById($id);

        if (!$signature) {
            throw new NotFoundException('Digital signature not found');
        }

        $this->repository->delete($id);
    }
}
