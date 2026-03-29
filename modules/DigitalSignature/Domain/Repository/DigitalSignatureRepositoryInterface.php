<?php

namespace Modules\DigitalSignature\Domain\Repository;

use Modules\DigitalSignature\Domain\DigitalSignature;

interface DigitalSignatureRepositoryInterface
{
    public function findById(string $id): ?DigitalSignature;

    public function save(DigitalSignature $signature): void;
}
