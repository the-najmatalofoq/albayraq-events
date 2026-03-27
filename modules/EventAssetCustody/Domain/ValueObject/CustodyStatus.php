<?php
// modules/EventAssetCustody/Domain/ValueObject/CustodyStatus.php
declare(strict_types=1);

namespace Modules\EventAssetCustody\Domain\ValueObject;

enum CustodyStatus: string
{
    case HANDED_OVER = 'handed_over';
    case RETURNED = 'returned';
    case LOST = 'lost';
    case DAMAGED = 'damaged';

    public function label(): string
    {
        return match ($this) {
            self::HANDED_OVER => 'Handed Over',
            self::RETURNED => 'Returned',
            self::LOST => 'Lost',
            self::DAMAGED => 'Damaged',
        };
    }
}
