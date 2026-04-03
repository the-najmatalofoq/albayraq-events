<?php
// modules/User/Domain/BankDetail.php
declare(strict_types=1);

namespace Modules\User\Domain;

use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\User\Domain\ValueObject\BankDetailId;
use Modules\User\Domain\ValueObject\UserId;

final class BankDetail extends AggregateRoot
{
    private function __construct(
        public readonly BankDetailId $uuid,
        public readonly UserId $userId,
        public private(set) string $accountOwner,
        public private(set) string $bankName,
        public private(set) string $iban,
        public private(set) ?string $accountContact = null,
    ) {}

    public static function create(
        BankDetailId $uuid,
        UserId $userId,
        string $accountOwner,
        string $bankName,
        string $iban,
        ?string $accountContact = null,
    ): self {
        return new self(
            uuid: $uuid,
            userId: $userId,
            accountOwner: $accountOwner,
            bankName: $bankName,
            iban: $iban,
            accountContact: $accountContact,
        );
    }

    public static function fromPersistence(
        BankDetailId $uuid,
        UserId $userId,
        string $accountOwner,
        string $bankName,
        string $iban,
        ?string $accountContact = null,
    ): self {
        return new self(
            uuid: $uuid,
            userId: $userId,
            accountOwner: $accountOwner,
            bankName: $bankName,
            iban: $iban,
            accountContact: $accountContact,
        );
    }

    public function updateDetails(
        string $accountOwner,
        string $bankName,
        string $iban,
        ?string $accountContact,
    ): void {
        $this->accountOwner = $accountOwner;
        $this->bankName = $bankName;
        $this->iban = $iban;
        $this->accountContact = $accountContact;
    }

    public function id(): Identity
    {
        return $this->uuid;
    }
}
