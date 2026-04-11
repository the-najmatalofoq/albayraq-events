<?php
// filePath: modules/EventPositionApplication/Application/Commands/Crm/CrmUpdateEventPositionApplicationCommand.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Application\Commands\Crm;

final readonly class CrmUpdateEventPositionApplicationCommand
{
    public function __construct(
        public string $id,
        public string $userId,
        public string $positionId,
        public string $status,
        public float $rankingScore,
    ) {}
}
