<?php
declare(strict_types=1);

namespace Modules\DigitalSignature\Infrastructure\Persistence;

use DateTimeImmutable;
use Modules\DigitalSignature\Domain\DigitalSignature;
use Modules\DigitalSignature\Domain\ValueObject\DigitalSignatureId;
use Modules\DigitalSignature\Infrastructure\Persistence\Eloquent\DigitalSignatureModel;

final class DigitalSignatureReflector
{
    public static function fromModel(DigitalSignatureModel $model): DigitalSignature
    {
        // todo: make variable (private method) for the signedAt, createdAt and updatedAt to avoid code duplication
        return new DigitalSignature(
            uuid: DigitalSignatureId::fromString($model->id),
            contractId: $model->contract_id,
            signatureSvg: $model->signature_svg,
            ipAddress: $model->ip_address,
            userAgent: $model->user_agent,
            signedAt: $model->signed_at instanceof DateTimeImmutable
                ? $model->signed_at
                : new DateTimeImmutable($model->signed_at),
            createdAt: $model->created_at instanceof DateTimeImmutable
                ? $model->created_at
                : new DateTimeImmutable($model->created_at),
            updatedAt: $model->updated_at ? ($model->updated_at instanceof DateTimeImmutable
                ? $model->updated_at
                : new DateTimeImmutable($model->updated_at)) : null,
        );
    }
}
