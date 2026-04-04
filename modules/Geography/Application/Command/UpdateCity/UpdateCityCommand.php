<?php
declare(strict_types=1);

namespace Modules\Geography\Application\Command\UpdateCity;

final readonly class UpdateCityCommand
{
    public function __construct(public string $id, public array $data) {}
}
