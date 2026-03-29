<?php

namespace Modules\EventExperienceCertificate\Infrastructure\Persistence\Eloquent;

use DateTimeImmutable;
use Modules\EventExperienceCertificate\Domain\EventExperienceCertificate;
use Modules\EventExperienceCertificate\Domain\Repository\EventExperienceCertificateRepositoryInterface;

class EloquentEventExperienceCertificateRepository implements EventExperienceCertificateRepositoryInterface
{
    public function findById(string $id): ?EventExperienceCertificate
    {
        $model = EventExperienceCertificateModel::find($id);
        if (!$model) {
            return null;
        }

        return new EventExperienceCertificate(
            $model->id,
            $model->event_participation_id,
            (float) $model->total_hours,
            (float) $model->average_score,
            DateTimeImmutable::createFromMutable($model->issued_at),
            $model->verification_code,
        );
    }

    public function save(EventExperienceCertificate $certificate): void
    {
        $model = EventExperienceCertificateModel::findOrNew($certificate->id);
        $model->event_participation_id = $certificate->eventParticipationId;
        $model->total_hours = $certificate->totalHours;
        $model->average_score = $certificate->averageScore;
        $model->issued_at = $certificate->issuedAt->format('Y-m-d H:i:s');
        $model->verification_code = $certificate->verificationCode;
        $model->save();
    }
}
