<?php
declare(strict_types=1);

namespace Modules\DigitalSignature\Application\Query\GetOneById;

final readonly class GetDigitalSignatureByIdQuery
{
    public function __construct(
        public string $id,
    ) {}
}
