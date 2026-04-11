<?php
// filePath: modules/EventPositionApplication/Application/Commands/Crm/CrmCreateEventPositionApplicationCommand.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Application\Commands\Crm;

final readonly class CrmCreateEventPositionApplicationCommand
{
    public function __construct(
        public string $userId,
        public string $positionId,
        public string $status = 'pending',
        public float $rankingScore = 0.0,
    ) {}
}
