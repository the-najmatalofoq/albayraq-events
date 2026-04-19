<?php
// modules/Currency/Application/Command/UpdateCurrency/UpdateCurrencyCommand.php
declare(strict_types=1);

namespace Modules\Currency\Application\Command\UpdateCurrency;

use Modules\Shared\Domain\ValueObject\TranslatableText;

final readonly class UpdateCurrencyCommand
{
    public function __construct(
        public string $id,
        public ?TranslatableText $name,
        public ?string $code,
        public ?string $symbol,
        public ?bool $isActive,
    ) {}
}
