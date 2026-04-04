<?php
declare(strict_types=1);

namespace Modules\Geography\Application\Command\DeleteCity;

final readonly class DeleteCityCommand
{
    public function __construct(public string $id) {}
}
