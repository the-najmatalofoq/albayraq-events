<?php
// modules/EventBreakRequest/Application/Commands/RejectBreak/RejectBreakCommand.php
declare(strict_types=1);

namespace Modules\EventBreakRequest\Application\Command\RejectBreak;

final readonly class RejectBreakCommand
{
    public function __construct(
        public string $breakRequestId,
        public string $rejectorId,
        public string $reason
    ) {}
}
