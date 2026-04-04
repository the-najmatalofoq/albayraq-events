<?php
declare(strict_types=1);

namespace Modules\Geography\Application\Command\CreateNationality;

final readonly class CreateNationalityCommand
{
    public function __construct(public array $data) {}
}
