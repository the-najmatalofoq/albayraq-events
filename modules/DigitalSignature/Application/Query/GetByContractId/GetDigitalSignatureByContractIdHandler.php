<?php
declare(strict_types=1);

namespace Modules\DigitalSignature\Application\Query\GetByContractId;

use Modules\DigitalSignature\Domain\DigitalSignature;
use Modules\DigitalSignature\Domain\Repository\DigitalSignatureRepositoryInterface;
use Modules\Shared\Domain\Exception\NotFoundException;

final readonly class GetDigitalSignatureByContractIdHandler
{
    public function __construct(
        private DigitalSignatureRepositoryInterface $repository,
    ) {}

    public function handle(GetDigitalSignatureByContractIdQuery $query): DigitalSignature
    {
        $signature = $this->repository->findByContractId($query->contractId);

        if (!$signature) {
            throw new NotFoundException('Digital signature not found for this contract');
        }

        return $signature;
    }
}
