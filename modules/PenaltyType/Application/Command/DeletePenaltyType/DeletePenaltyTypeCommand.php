<?php
// modules/PenaltyType/Application/Command/DeletePenaltyType/DeletePenaltyTypeCommand.php
declare(strict_types=1);

namespace Modules\PenaltyType\Application\Command\DeletePenaltyType;

final readonly class DeletePenaltyTypeCommand
{
    public function __construct(
        public string $id
    ) {}
}
