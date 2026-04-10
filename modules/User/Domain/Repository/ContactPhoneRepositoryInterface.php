<?php
declare(strict_types=1);

namespace Modules\User\Domain\Repository;

use Modules\Shared\Domain\Repository\FilterableRepositoryInterface;
use Modules\Shared\Domain\ValueObject\FilterCriteria;
use Modules\User\Domain\ContactPhone;
use Modules\User\Domain\ValueObject\UserId;
use Modules\User\Domain\ValueObject\ContactPhoneId;

interface ContactPhoneRepositoryInterface
{
    public function save(ContactPhone $contactPhone): void;
    public function findByUserId(UserId $userId): ?ContactPhone;
    public function findById(ContactPhoneId $uuid): ?ContactPhone;
    public function nextIdentity(): ContactPhoneId;
    public function delete(ContactPhoneId $uuid): void;
}
