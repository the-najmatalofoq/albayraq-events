<?php
declare(strict_types=1);

namespace Modules\DigitalSignature\Application\Command\Update;

use Modules\DigitalSignature\Domain\Exception\DigitalSignatureNotFoundException;
use Modules\DigitalSignature\Domain\Repository\DigitalSignatureRepositoryInterface;
use Modules\DigitalSignature\Domain\ValueObject\DigitalSignatureId;

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
            throw new DigitalSignatureNotFoundException($command->id);
        }

        $signature->updateSignature($command->signatureSvg);

        $signature->updateMetadata($command->ipAddress, $command->userAgent);

        $this->repository->save($signature);
    }
}
