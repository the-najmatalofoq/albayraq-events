<?php
declare(strict_types=1);

namespace Modules\Geography\Application\Command\CreateState;

final readonly class CreateStateCommand
{
    public function __construct(public array $data) {}
}
