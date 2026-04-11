<?php

namespace Modules\EventExperienceCertificate\Domain\Repository;

use Modules\EventExperienceCertificate\Domain\EventExperienceCertificate;
// fix: use the fiter in the listAll also.

// fix: use the FilterableRepositoryInterface
interface EventExperienceCertificateRepositoryInterface
{
    public function findById(string $id): ?EventExperienceCertificate;

    public function save(EventExperienceCertificate $certificate): void;
}
