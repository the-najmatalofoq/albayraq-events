<?php
// modules/EventBreakRequest/Application/Commands/RequestBreak/RequestBreakCommand.php
declare(strict_types=1);

namespace Modules\EventBreakRequest\Application\Command\RequestBreak;

use Carbon\CarbonImmutable;

final readonly class RequestBreakCommand
{
    public function __construct(
        public string $eventId,
        public string $participationId,
        public string $requestedByUserId,
        public CarbonImmutable $date,
        public CarbonImmutable $startTime,
        public CarbonImmutable $endTime
    ) {}
}
