<?php
declare(strict_types=1);

namespace Modules\DigitalSignature\Application\Command\Update;

use Modules\DigitalSignature\Domain\Repository\DigitalSignatureRepositoryInterface;
use Modules\DigitalSignature\Domain\ValueObject\DigitalSignatureId;
use Modules\Shared\Domain\Exception\NotFoundException;

// todo: make exception for each module, for example DigitalSignatureNotFoundException
final readonly class UpdateDigitalSignatureHandler
{
    public function __construct(
        private DigitalSignatureRepositoryInterface $repository,
    ) {}

    public function handle(UpdateDigitalSignatureCommand $command): void
    {
        $id = DigitalSignatureId::fromString($command->id);

        $signature = $this->repository->findById($id);

        if (!$signature) {
            throw new NotFoundException('Digital signature not found');
        }

        $signature->updateSignature(
            $command->signatureSvg,
            $command->ipAddress,
            $command->userAgent
        );

        $this->repository->save($signature);
    }
}
