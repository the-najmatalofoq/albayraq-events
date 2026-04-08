<?php
// modules/ViolationType/Application/Command/DeleteViolationType/DeleteViolationTypeCommand.php
declare(strict_types=1);

namespace Modules\ViolationType\Application\Command\DeleteViolationType;

final readonly class DeleteViolationTypeCommand
{
    public function __construct(
        public string $id
    ) {}
}
