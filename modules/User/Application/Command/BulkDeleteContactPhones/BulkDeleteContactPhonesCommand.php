<?php
declare(strict_types=1);

namespace Modules\User\Application\Command\BulkDeleteContactPhones;

final readonly class BulkDeleteContactPhonesCommand
{
    /** @param list<string> $contactPhoneIds */
    public function __construct(
        public string $userId,
        public array $contactPhoneIds,
    ) {}
}
