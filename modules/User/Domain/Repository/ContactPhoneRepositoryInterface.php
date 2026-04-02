<?php
// modules/User/Domain/Repository/ContactPhoneRepositoryInterface.php
declare(strict_types=1);

namespace Modules\User\Domain\Repository;

use Modules\User\Domain\ContactPhone;
use Modules\User\Domain\ValueObject\UserId;
use Modules\User\Domain\ValueObject\ContactPhoneId;

interface ContactPhoneRepositoryInterface
{
    public function save(ContactPhone $contactPhone): void;
    public function findByUserId(UserId $userId): array;
    public function findById(ContactPhoneId $uuid): ?ContactPhone;
    public function nextIdentity(): ContactPhoneId;
}
