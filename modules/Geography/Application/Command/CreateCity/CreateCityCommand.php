<?php
declare(strict_types=1);

namespace Modules\Geography\Application\Command\CreateCity;

final readonly class CreateCityCommand
{
    public function __construct(public array $data) {}
}
