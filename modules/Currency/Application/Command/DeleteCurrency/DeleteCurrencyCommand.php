<?php
// modules/Currency/Application/Command/DeleteCurrency/DeleteCurrencyCommand.php
declare(strict_types=1);

namespace Modules\Currency\Application\Command\DeleteCurrency;

final readonly class DeleteCurrencyCommand
{
    public function __construct(
        public string $id
    ) {
    }
}
