<?php
// modules/User/Domain/Repository/ContactPhoneRepositoryInterface.php
declare(strict_types=1);

namespace Modules\User\Domain\Repository;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\User\Domain\ContactPhone;
use Modules\User\Domain\ValueObject\UserId;
use Modules\User\Domain\ValueObject\ContactPhoneId;

interface ContactPhoneRepositoryInterface
{
    public function save(ContactPhone $contactPhone): void;
    public function findByUserId(UserId $userId): array;
    public function findById(ContactPhoneId $uuid): ?ContactPhone;
    public function nextIdentity(): ContactPhoneId;
    public function delete(ContactPhoneId $uuid): void;
    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator;
    public function all(array $filters = []): Collection;
    /** @param list<ContactPhoneId> $ids */
    public function deleteBulk(UserId $userId, array $ids): void;
}
