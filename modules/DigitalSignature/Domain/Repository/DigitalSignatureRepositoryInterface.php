<?php
declare(strict_types=1);

namespace Modules\DigitalSignature\Domain\Repository;

use Modules\DigitalSignature\Domain\DigitalSignature;
use Modules\DigitalSignature\Domain\ValueObject\DigitalSignatureId;
use Modules\Shared\Domain\ValueObject\FilterCriteria;
use Modules\Shared\Domain\ValueObject\PaginationCriteria;
use Modules\Shared\Domain\ValueObject\SortCriteria;
// fix: use the fiter in the listAll also.

// fix: use the FilterableRepositoryInterface
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
    public function findAll(?FilterCriteria $filters = null, ?SortCriteria $sort = null): array;

    /**
     * @return array{data: DigitalSignature[], total: int}
     */
    public function findAllPaginated(
        PaginationCriteria $pagination,
        ?FilterCriteria $filters = null,
        ?SortCriteria $sort = null
    ): array;
}
