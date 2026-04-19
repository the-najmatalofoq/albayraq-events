<?php
// modules/ParticipationViolation/Application/Query/GetParticipationViolation/GetParticipationViolationQuery.php
declare(strict_types=1);

namespace Modules\ParticipationViolation\Application\Query\GetParticipationViolation;

final readonly class GetParticipationViolationQuery
{
    public function __construct(
        public string $id
    ) {}
}
