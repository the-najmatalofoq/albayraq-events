<?php
declare(strict_types=1);

namespace Modules\DigitalSignature\Application\Command\Delete;

final readonly class DeleteDigitalSignatureCommand
{
    public function __construct(
        public string $id,
    ) {}
}
