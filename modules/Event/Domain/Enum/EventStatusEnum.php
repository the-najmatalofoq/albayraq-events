<?php
// modules/Event/Domain/Enum/EventStatusEnum.php
declare(strict_types=1);

namespace Modules\Event\Domain\Enum;

enum EventStatusEnum: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case ONGOING = 'ongoing';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::PUBLISHED => 'Published',
            self::ONGOING => 'Ongoing',
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
        };
    }
}
