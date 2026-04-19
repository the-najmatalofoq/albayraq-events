<?php
// modules/Currency/Application/Command/DeleteCurrency/DeleteCurrencyHandler.php
declare(strict_types=1);

namespace Modules\Currency\Application\Command\DeleteCurrency;

use Modules\Currency\Domain\ValueObject\CurrencyId;
use Modules\Currency\Domain\Repository\CurrencyRepositoryInterface;

final readonly class DeleteCurrencyHandler
{
    public function __construct(
        private CurrencyRepositoryInterface $repository
    ) {
    }

    public function handle(DeleteCurrencyCommand $command): void
    {
        $id = CurrencyId::fromString($command->id);
        $this->repository->delete($id);
    }
}
