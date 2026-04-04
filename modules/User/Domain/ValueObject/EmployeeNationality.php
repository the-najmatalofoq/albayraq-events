<?php
// modules/User/Domain/ValueObject/EmployeeNationality.php
declare(strict_types=1);

namespace Modules\User\Domain\ValueObject;

use Modules\Geography\Domain\ValueObject\NationalityId;

/**
 * Value Object to represent a single nationality entry for an employee profile.
 */
final readonly class EmployeeNationality
{
    public function __construct(
        public NationalityId $nationalityId,
        public bool $isPrimary = false
    ) {
    }

    public static function create(string $nationalityId, bool $isPrimary = false): self
    {
        return new self(new NationalityId($nationalityId), $isPrimary);
    }
}
