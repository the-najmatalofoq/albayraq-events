<?php
declare(strict_types=1);

namespace Modules\Geography\Application\Command\UpdateState;

final readonly class UpdateStateCommand
{
    public function __construct(public string $id, public array $data) {}
}
