<?php
// modules/DeductionType/Application/Command/DeleteDeductionType/DeleteDeductionTypeCommand.php
declare(strict_types=1);

namespace Modules\DeductionType\Application\Command\DeleteDeductionType;

final readonly class DeleteDeductionTypeCommand
{
    public function __construct(
        public string $id
    ) {}
}
