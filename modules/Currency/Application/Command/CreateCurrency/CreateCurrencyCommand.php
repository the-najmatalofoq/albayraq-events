<?php
// modules/Currency/Application/Command/CreateCurrency/CreateCurrencyCommand.php
declare(strict_types=1);

namespace Modules\Currency\Application\Command\CreateCurrency;

use Modules\Shared\Domain\ValueObject\TranslatableText;

final readonly class CreateCurrencyCommand
{
    public function __construct(
        public TranslatableText $name,
        public string $code,
        public string $symbol,
        public bool $isActive = true,
    ) {}
}
