<?php
declare(strict_types=1);

namespace Modules\Geography\Application\Command\DeleteCountry;

final readonly class DeleteCountryCommand
{
    public function __construct(public string $id) {}
}
