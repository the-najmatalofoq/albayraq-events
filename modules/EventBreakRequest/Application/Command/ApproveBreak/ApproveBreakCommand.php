<?php
// modules/EventBreakRequest/Application/Commands/ApproveBreak/ApproveBreakCommand.php
declare(strict_types=1);

namespace Modules\EventBreakRequest\Application\Command\ApproveBreak;

final readonly class ApproveBreakCommand
{
    public function __construct(
        public string $breakRequestId,
        public string $approverId,
        public ?string $coverEmployeeId = null
    ) {}
}
