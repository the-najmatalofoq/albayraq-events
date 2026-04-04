<?php
declare(strict_types=1);

namespace Modules\Geography\Application\Command\DeleteNationality;

final readonly class DeleteNationalityCommand
{
    public function __construct(public string $id) {}
}
