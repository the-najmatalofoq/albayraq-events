<?php
// modules/Currency/Application/Command/UpdateCurrency/UpdateCurrencyHandler.php
declare(strict_types=1);

namespace Modules\Currency\Application\Command\UpdateCurrency;

use Modules\Currency\Domain\ValueObject\CurrencyId;
use Modules\Currency\Domain\Repository\CurrencyRepositoryInterface;
use Modules\Shared\Domain\ValueObject\TranslatableText;

final readonly class UpdateCurrencyHandler
{
    public function __construct(
        private CurrencyRepositoryInterface $repository
    ) {}

    public function handle(UpdateCurrencyCommand $command): void
    {
        $id = CurrencyId::fromString($command->id);
        $currency = $this->repository->findById($id);

        if (!$currency) {
            throw new \RuntimeException("Currency not found");
        }
        $reasonText = $command->name ? TranslatableText::fromMixed($command->name) : $currency->name;

        $mergedTranslations = array_merge($currency->name->values, $reasonText->values);
        $currency->update(
            name: TranslatableText::fromMixed($mergedTranslations),
            code: $command->code ?? $currency->code,
            symbol: $command->symbol ?? $currency->symbol,
            isActive: $command->isActive ?? $currency->isActive,
        );

        $this->repository->save($currency);
    }
}
