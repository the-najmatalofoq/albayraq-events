<?php

declare(strict_types=1);

namespace Modules\User\Domain\Exception;

use Modules\Shared\Domain\Exception\NotFoundException;

final class JoinRequestNotFoundException extends NotFoundException
{
    public static function forId(string $id): self
    {
        return new self("Join request with ID {$id} not found.");
    }
}
