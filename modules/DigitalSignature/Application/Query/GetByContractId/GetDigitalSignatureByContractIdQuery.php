<?php
declare(strict_types=1);

namespace Modules\DigitalSignature\Application\Query\GetByContractId;

final readonly class GetDigitalSignatureByContractIdQuery
{
    public function __construct(
        public string $contractId,
    ) {}
}
