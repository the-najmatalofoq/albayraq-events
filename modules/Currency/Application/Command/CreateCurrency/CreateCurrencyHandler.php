<?php
// modules/Currency/Application/Command/CreateCurrency/CreateCurrencyHandler.php
declare(strict_types=1);

namespace Modules\Currency\Application\Command\CreateCurrency;

use Modules\Currency\Domain\Currency;
use Modules\Currency\Domain\ValueObject\CurrencyId;
use Modules\Currency\Domain\Repository\CurrencyRepositoryInterface;
use Modules\Shared\Domain\ValueObject\TranslatableText;

final readonly class CreateCurrencyHandler
{
    public function __construct(
        private CurrencyRepositoryInterface $repository
    ) {
    }

    public function handle(CreateCurrencyCommand $command): CurrencyId
    {
        $id = $this->repository->nextIdentity();

        $currency = Currency::create(
            uuid: $id,
            name: $command->name,
            code: $command->code,
            symbol: $command->symbol,
            isActive: $command->isActive,
        );

        $this->repository->save($currency);

        return $id;
    }
}
