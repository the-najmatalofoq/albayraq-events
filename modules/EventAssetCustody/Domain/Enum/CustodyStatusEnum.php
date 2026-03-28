<?php
// modules/EventAssetCustody/Domain/Enum/CustodyStatusEnum.php
declare(strict_types=1);

namespace Modules\EventAssetCustody\Domain\Enum;

enum CustodyStatusEnum: string
{
    case HANDED = 'handed';
    case RETURNED = 'returned';
    case DAMAGED = 'damaged';
    case LOST = 'lost';
}
