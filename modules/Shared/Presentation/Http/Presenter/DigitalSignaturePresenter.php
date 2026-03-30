<?php
declare(strict_types=1);

namespace Modules\Shared\Presentation\Http\Presenter;

use Modules\DigitalSignature\Domain\DigitalSignature;

final class DigitalSignaturePresenter
{
    public static function toArray(DigitalSignature $signature): array
    {
        return [
            'id' => $signature->id->value,
            'contract_id' => $signature->contractId->value,
            'signature_svg' => $signature->signatureSvg,
            'ip_address' => $signature->ipAddress,
            'user_agent' => $signature->userAgent,
            'signed_at' => $signature->signedAt->format('Y-m-d H:i:s'),
            'created_at' => $signature->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $signature->updatedAt?->format('Y-m-d H:i:s'),
        ];
    }

    public static function toSummaryArray(DigitalSignature $signature): array
    {
        return [
            'id' => $signature->id->value,
            'contract_id' => $signature->contractId->value,
            'signed_at' => $signature->signedAt->format('Y-m-d H:i:s'),
            'created_at' => $signature->createdAt->format('Y-m-d H:i:s'),
        ];
    }
}
