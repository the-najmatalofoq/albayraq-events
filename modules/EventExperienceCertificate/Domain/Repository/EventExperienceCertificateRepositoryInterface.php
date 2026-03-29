<?php

namespace Modules\EventExperienceCertificate\Domain\Repository;

use Modules\EventExperienceCertificate\Domain\EventExperienceCertificate;

interface EventExperienceCertificateRepositoryInterface
{
    public function findById(string $id): ?EventExperienceCertificate;

    public function save(EventExperienceCertificate $certificate): void;
}
