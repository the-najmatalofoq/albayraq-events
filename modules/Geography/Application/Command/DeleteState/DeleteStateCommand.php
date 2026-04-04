<?php
declare(strict_types=1);

namespace Modules\Geography\Application\Command\DeleteState;

final readonly class DeleteStateCommand
{
    public function __construct(public string $id) {}
}
