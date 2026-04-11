<?php
// modules/EventRoleCapability/Presentation/Http/Presenter/CrmEventRoleCapabilityPresenter.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Presentation\Http\Presenter;

use Modules\EventRoleCapability\Domain\EventRoleCapability;

final readonly class CrmEventRoleCapabilityPresenter
{
    public function present(EventRoleCapability $capability): array
    {
        return [
            'id' => $capability->uuid->value,
            'assignment_id' => $capability->assignmentId->value,
            'capability_key' => $capability->capabilityKey,
            'is_granted' => $capability->isGranted,
            'is_deleted' => $capability->isDeleted(),
            'deleted_at' => $capability->deletedAt?->format(\DateTimeInterface::ATOM),
        ];
    }

    public function presentCollection(iterable $capabilities): array
    {
        $result = [];
        foreach ($capabilities as $capability) {
            $result[] = $this->present($capability);
        }
        return $result;
    }
}
