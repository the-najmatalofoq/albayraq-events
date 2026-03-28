<?php
// modules/EventAssetCustody/Infrastructure/Persistence/EventAssetCustodyReflector.php
declare(strict_types=1);

namespace Modules\EventAssetCustody\Infrastructure\Persistence;

use Modules\EventAssetCustody\Domain\EventAssetCustody;
use Modules\EventAssetCustody\Domain\ValueObject\CustodyId;
use Modules\EventAssetCustody\Domain\Enum\CustodyStatusEnum;
use Modules\EventParticipation\Domain\ValueObject\ParticipationId;
use Modules\User\Domain\ValueObject\UserId;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\EventAssetCustody\Infrastructure\Persistence\Eloquent\EventAssetCustodyModel;
use DateTimeImmutable;

final class EventAssetCustodyReflector
{
    public static function fromModel(EventAssetCustodyModel $model): EventAssetCustody
    {
        $reflection = new \ReflectionClass(EventAssetCustody::class);
        $custody = $reflection->newInstanceWithoutConstructor();

        $properties = [
            'uuid'              => CustodyId::fromString($model->id),
            'participationId'   => ParticipationId::fromString($model->event_participation_id),
            'itemName'          => TranslatableText::fromArray($model->item_name),
            'status'            => CustodyStatusEnum::from($model->status),
            'description'       => $model->description ? TranslatableText::fromArray($model->description) : null,
            'handedAt'          => $model->handed_at ? DateTimeImmutable::createFromInterface($model->handed_at) : new DateTimeImmutable(),
            'returnedAt'        => $model->returned_at ? DateTimeImmutable::createFromInterface($model->returned_at) : null,
            'handedBy'          => UserId::fromString($model->handed_by),
        ];

        foreach ($properties as $field => $value) {
            $prop = $reflection->getProperty($field);
            $prop->setValue($custody, $value);
        }

        return $custody;
    }
}
