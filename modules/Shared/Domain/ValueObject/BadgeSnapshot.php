<?php
// modules/Shared/Domain/ValueObject/BadgeSnapshot.php
declare(strict_types=1);

namespace Modules\Shared\Domain\ValueObject;

use Modules\Shared\Domain\ValueObject;

final readonly class BadgeSnapshot extends ValueObject
{
    public function __construct(
        public string $employeeName,
        public string $employeeNumber,
        public string $jobTitle,
        public string $eventName,
        public string $companyLogoPath,
        public string $eventLogoPath
    ) {}

    public function equals(ValueObject $other): bool
    {
        return $other instanceof self && (array)$this === (array)$other;
    }
}
