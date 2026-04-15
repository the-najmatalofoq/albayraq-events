<?php
// modules/Currency/Domain/Repository/CurrencyRepositoryInterface.php
declare(strict_types=1);

namespace Modules\Currency\Domain\Repository;

use Modules\Currency\Domain\Currency;
use Modules\Currency\Domain\ValueObject\CurrencyId;
use Modules\Shared\Domain\Repository\FilterableRepositoryInterface;

interface CurrencyRepositoryInterface extends FilterableRepositoryInterface
{
    public function nextIdentity(): CurrencyId;

    public function save(Currency $currency): void;

    public function findById(CurrencyId $id): ?Currency;

    /**
     * @return Currency[]
     */
    public function findAll(): array;

    public function delete(CurrencyId $id): void;
}
