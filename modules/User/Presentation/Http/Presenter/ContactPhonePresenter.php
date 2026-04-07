<?php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Presenter;

use Modules\User\Domain\ContactPhone;

final class ContactPhonePresenter
{
    /** @param list<ContactPhone> $contactPhones */
    public static function collection(array $contactPhones): array
    {
        return array_map(fn($item) => self::fromDomain($item), $contactPhones);
    }

    public static function fromDomain(ContactPhone $contactPhone): array
    {
        return [
            'id' => $contactPhone->uuid->value,
            'name' => $contactPhone->name,
            'phone' => $contactPhone->phone,
            'relation' => $contactPhone->relation,
        ];
    }
}
