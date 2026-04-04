<?php
declare(strict_types=1);

namespace Modules\Geography\Application\Command\UpdateCountry;

final readonly class UpdateCountryCommand
{
    public function __construct(public string $id, public array $data) {}
}
