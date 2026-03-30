<?php
declare(strict_types=1);

namespace Modules\DigitalSignature\Application\Query\GetAll;

use Modules\DigitalSignature\Domain\DigitalSignature;
use Modules\DigitalSignature\Domain\Repository\DigitalSignatureRepositoryInterface;

final readonly class GetAllDigitalSignaturesHandler
{
    public function __construct(
        private DigitalSignatureRepositoryInterface $repository,
    ) {}

    /**
     * @return DigitalSignature[]
     */
    public function handle(GetAllDigitalSignaturesQuery $query): array
    {
        return $this->repository->findAll();
    }
}
