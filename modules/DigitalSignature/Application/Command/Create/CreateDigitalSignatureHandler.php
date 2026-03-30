<?php
declare(strict_types=1);

namespace Modules\DigitalSignature\Application\Command\Create;

use Modules\DigitalSignature\Domain\DigitalSignature;
use Modules\DigitalSignature\Domain\Repository\DigitalSignatureRepositoryInterface;
use Modules\DigitalSignature\Domain\ValueObject\DigitalSignatureId;

final readonly class CreateDigitalSignatureHandler
{
    public function __construct(
        private DigitalSignatureRepositoryInterface $repository,
    ) {}

    public function handle(CreateDigitalSignatureCommand $command): DigitalSignature
    {
        $id = $this->repository->nextIdentity();

        $signature = DigitalSignature::create(
            uuid: $id,
            contractId: $command->contractId,
            signatureSvg: $command->signatureSvg,
            ipAddress: $command->ipAddress,
            userAgent: $command->userAgent,
            signedAt: $command->signedAt,
        );

        $this->repository->save($signature);

        return $signature;
    }
}
