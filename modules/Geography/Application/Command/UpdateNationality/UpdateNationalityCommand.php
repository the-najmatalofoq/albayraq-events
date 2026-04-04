<?php
declare(strict_types=1);

namespace Modules\Geography\Application\Command\UpdateNationality;

final readonly class UpdateNationalityCommand
{
    public function __construct(public string $id, public array $data) {}
}
