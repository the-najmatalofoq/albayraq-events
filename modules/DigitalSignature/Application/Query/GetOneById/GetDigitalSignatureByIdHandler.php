<?php
declare(strict_types=1);

namespace Modules\DigitalSignature\Application\Query\GetOneById;

use Modules\DigitalSignature\Domain\DigitalSignature;
use Modules\DigitalSignature\Domain\Repository\DigitalSignatureRepositoryInterface;
use Modules\DigitalSignature\Domain\ValueObject\DigitalSignatureId;
use Modules\Shared\Domain\Exception\NotFoundException;

final readonly class GetDigitalSignatureByIdHandler
{
    public function __construct(
        private DigitalSignatureRepositoryInterface $repository,
    ) {}

    public function handle(GetDigitalSignatureByIdQuery $query): DigitalSignature
    {
        $id = DigitalSignatureId::fromString($query->id);

        $signature = $this->repository->findById($id);

        if (!$signature) {
            throw new NotFoundException('Digital signature not found');
        }

        return $signature;
    }
}
