<?php

namespace Modules\DigitalSignature\Infrastructure\Persistence\Eloquent;

use DateTimeImmutable;
use Modules\DigitalSignature\Domain\DigitalSignature;
use Modules\DigitalSignature\Domain\Repository\DigitalSignatureRepositoryInterface;

class EloquentDigitalSignatureRepository implements DigitalSignatureRepositoryInterface
{
    public function findById(string $id): ?DigitalSignature
    {
        $model = DigitalSignatureModel::find($id);
        if (!$model) {
            return null;
        }

        return new DigitalSignature(
            $model->id,
            $model->contract_id,
            $model->signature_svg,
            $model->ip_address,
            $model->user_agent,
            DateTimeImmutable::createFromMutable($model->signed_at),
        );
    }

    public function save(DigitalSignature $signature): void
    {
        $model = DigitalSignatureModel::findOrNew($signature->id);
        $model->contract_id = $signature->contractId;
        $model->signature_svg = $signature->signatureSvg;
        $model->ip_address = $signature->ipAddress;
        $model->user_agent = $signature->userAgent;
        $model->signed_at = $signature->signedAt->format('Y-m-d H:i:s');
        $model->save();
    }
}
