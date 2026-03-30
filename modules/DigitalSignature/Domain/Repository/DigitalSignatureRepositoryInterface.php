<?php
declare(strict_types=1);

namespace Modules\DigitalSignature\Domain\Repository;

use Modules\DigitalSignature\Domain\DigitalSignature;
use Modules\DigitalSignature\Domain\ValueObject\DigitalSignatureId;

interface DigitalSignatureRepositoryInterface
{
    public function nextIdentity(): DigitalSignatureId;

    public function save(DigitalSignature $signature): void;

    public function findById(DigitalSignatureId $id): ?DigitalSignature;

    public function findByContractId(string $contractId): ?DigitalSignature;

    public function delete(DigitalSignatureId $id): void;

    /**
     * @return DigitalSignature[]
     */
    public function findAll(): array;

    /**
     * @param array<string, mixed> $filters
     * @return array{data: DigitalSignature[], total: int}
     */
    public function findAllPaginated(
        int $page = 1,
        int $perPage = 15,
        array $filters = [],
        string $orderBy = 'signed_at',
        string $orderDirection = 'desc'
    ): array;
}
