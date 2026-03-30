<?php
declare(strict_types=1);

namespace Modules\DigitalSignature\Infrastructure\Persistence;

use DateTimeImmutable;
use Modules\DigitalSignature\Domain\DigitalSignature;
use Modules\DigitalSignature\Domain\ValueObject\DigitalSignatureId;
use Modules\DigitalSignature\Infrastructure\Persistence\Eloquent\DigitalSignatureModel;
use Modules\EventContract\Domain\ValueObject\ContractId;

final class DigitalSignatureReflector
{
    public static function fromModel(DigitalSignatureModel $model): DigitalSignature
    {
        $reflection = new \ReflectionClass(DigitalSignature::class);
        $signature = $reflection->newInstanceWithoutConstructor();

        $properties = [
            'id' => DigitalSignatureId::fromString($model->id),
            'contractId' => ContractId::fromString($model->contract_id),
            'signatureSvg' => $model->signature_svg,
            'ipAddress' => $model->ip_address,
            'userAgent' => $model->user_agent,
            'signedAt' => self::toDateTimeImmutable($model->signed_at),
            'createdAt' => self::toDateTimeImmutable($model->created_at),
            'updatedAt' => $model->updated_at ? self::toDateTimeImmutable($model->updated_at) : null,
        ];

        foreach ($properties as $field => $value) {
            $prop = $reflection->getProperty($field);
            $prop->setValue($signature, $value);
        }

        return $signature;
    }

    private static function toDateTimeImmutable($date): DateTimeImmutable
    {
        if ($date instanceof DateTimeImmutable) {
            return $date;
        }
        if ($date instanceof \DateTime) {
            return DateTimeImmutable::createFromInterface($date);
        }
        return new DateTimeImmutable($date);
    }
}
