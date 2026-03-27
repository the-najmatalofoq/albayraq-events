<?php
// modules/ParticipationViolation/Infrastructure/Persistence/ParticipationViolationReflector.php
declare(strict_types=1);

namespace Modules\ParticipationViolation\Infrastructure\Persistence;

use Modules\ParticipationViolation\Domain\ParticipationViolation;
use Modules\ParticipationViolation\Domain\ValueObject\ViolationId;
use Modules\ViolationType\Domain\ValueObject\ViolationTypeId;
use Modules\EventParticipation\Domain\ValueObject\ParticipationId;
use Modules\IAM\Domain\ValueObject\UserId;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\ParticipationViolation\Infrastructure\Persistence\Eloquent\ParticipationViolationModel;

final class ParticipationViolationReflector
{
    public static function fromModel(ParticipationViolationModel $model): ParticipationViolation
    {
        $reflection = new \ReflectionClass(ParticipationViolation::class);
        $violation = $reflection->newInstanceWithoutConstructor();

        $properties = [
            'uuid' => ViolationId::fromString($model->id),
            'participationId' => ParticipationId::fromString($model->event_participation_id),
            'violationTypeId' => ViolationTypeId::fromString($model->violation_type_id),
            'description' => TranslatableText::fromArray($model->description),
            'issuedBy' => UserId::fromString($model->issued_by),
            'occurredAt' => \DateTimeImmutable::createFromMutable($model->occurred_at),
            'createdAt' => \DateTimeImmutable::createFromMutable($model->created_at),
        ];

        foreach ($properties as $field => $value) {
            $prop = $reflection->getProperty($field);
            $prop->setValue($violation, $value);
        }

        return $violation;
    }
}
