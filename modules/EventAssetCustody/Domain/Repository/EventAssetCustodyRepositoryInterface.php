<?php
// modules/EventAssetCustody/Domain/Repository/EventAssetCustodyRepositoryInterface.php
declare(strict_types=1);

namespace Modules\EventAssetCustody\Domain\Repository;

use Modules\EventAssetCustody\Domain\EventAssetCustody;
use Modules\EventAssetCustody\Domain\ValueObject\CustodyId;
use Modules\EventParticipation\Domain\ValueObject\ParticipationId;

interface EventAssetCustodyRepositoryInterface
{
    public function nextIdentity(): CustodyId;

    public function save(EventAssetCustody $custody): void;

    public function findById(CustodyId $id): ?EventAssetCustody;

    public function findByParticipationId(ParticipationId $participationId): array;
}
