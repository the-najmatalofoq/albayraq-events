<?php
declare(strict_types=1);

namespace Modules\DigitalSignature\Application\Command\Create;

use Modules\DigitalSignature\Domain\DigitalSignature;
use Modules\DigitalSignature\Domain\Repository\DigitalSignatureRepositoryInterface;
use Modules\EventContract\Domain\ValueObject\ContractId;

final readonly class CreateDigitalSignatureHandler
{
    public function __construct(
        private DigitalSignatureRepositoryInterface $repository,
    ) {}

    public function handle(CreateDigitalSignatureCommand $command): DigitalSignature
    {
        $id = $this->repository->nextIdentity();

        $contractId = ContractId::fromString($command->contractId);

        $signature = DigitalSignature::createWithCustomTimestamp(
            id: $id,
            contractId: $contractId,
            signatureSvg: $command->signatureSvg,
            signedAt: $command->signedAt,
            ipAddress: $command->ipAddress,
            userAgent: $command->userAgent,
        );

        $this->repository->save($signature);

        return $signature;
    }
}
