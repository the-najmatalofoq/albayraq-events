<?php
// modules/ViolationType/Domain/Repository/ViolationTypeRepositoryInterface.php
declare(strict_types=1);

namespace Modules\ViolationType\Domain\Repository;

use Modules\ViolationType\Domain\ViolationType;
use Modules\ViolationType\Domain\ValueObject\ViolationTypeId;

interface ViolationTypeRepositoryInterface
{
    public function nextIdentity(): ViolationTypeId;

    public function save(ViolationType $violationType): void;

    public function findById(ViolationTypeId $id): ?ViolationType;

    public function listAll(): array;
}
