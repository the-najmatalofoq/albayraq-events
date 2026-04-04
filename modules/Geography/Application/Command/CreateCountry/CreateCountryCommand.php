<?php
declare(strict_types=1);

namespace Modules\Geography\Application\Command\CreateCountry;

final readonly class CreateCountryCommand
{
    public function __construct(public array $data) {}
}
