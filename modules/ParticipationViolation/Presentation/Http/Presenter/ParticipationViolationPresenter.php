<?php
// modules/ParticipationViolation/Presentation/Http/Presenter/ParticipationViolationPresenter.php
declare(strict_types=1);

namespace Modules\ParticipationViolation\Presentation\Http\Presenter;

use Modules\ParticipationViolation\Domain\ParticipationViolation;

final class ParticipationViolationPresenter
{
    public static function fromDomain(ParticipationViolation $violation): array
    {
        return [
            'id' => $violation->uuid->value,
            'event_participation_id' => $violation->participationId->value,
            'violation_type_id' => $violation->violationTypeId->value,
            'description' => $violation->description->toArray(),
            'issued_by' => $violation->issuedBy->value,
            'occurred_at' => $violation->occurredAt->format('Y-m-d H:i:s'),
            'created_at' => $violation->createdAt->format('Y-m-d H:i:s'),
        ];
    }
}
