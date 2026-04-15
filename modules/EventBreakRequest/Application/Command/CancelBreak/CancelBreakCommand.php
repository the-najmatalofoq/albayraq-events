<?php
// modules/EventBreakRequest/Application/Commands/CancelBreak/CancelBreakCommand.php
declare(strict_types=1);

namespace Modules\EventBreakRequest\Application\Command\CancelBreak;

final readonly class CancelBreakCommand
{
    public function __construct(
        public string $breakRequestId,
        public string $requestedByUserId
    ) {}
}
