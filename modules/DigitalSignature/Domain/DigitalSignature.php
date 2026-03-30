<?php

declare(strict_types=1);

namespace Modules\DigitalSignature\Domain;

use DateTimeImmutable;
use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\EventContract\Domain\ValueObject\ContractId;
use Modules\DigitalSignature\Domain\ValueObject\DigitalSignatureId;

final class DigitalSignature extends AggregateRoot
{
    private function __construct(
        public readonly DigitalSignatureId $id,
        public readonly ContractId $contractId,
        public private(set) string $signatureSvg,
        public private(set) ?string $ipAddress,
        public private(set) ?string $userAgent,
        public readonly DateTimeImmutable $signedAt,
        public readonly DateTimeImmutable $createdAt,
        public private(set) ?DateTimeImmutable $updatedAt = null,
    ) {}

    public static function create(
        DigitalSignatureId $id,
        ContractId $contractId,
        string $signatureSvg,
        ?string $ipAddress = null,
        ?string $userAgent = null,
    ): self {
        $now = new DateTimeImmutable();

        return new self(
            id: $id,
            contractId: $contractId,
            signatureSvg: $signatureSvg,
            ipAddress: $ipAddress,
            userAgent: $userAgent,
            signedAt: $now,
            createdAt: $now,
        );
    }

    public function updateSignature(string $newSignatureSvg): void
    {
        $this->signatureSvg = $newSignatureSvg;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function updateMetadata(?string $ipAddress = null, ?string $userAgent = null): void
    {
        $this->ipAddress = $ipAddress;
        $this->userAgent = $userAgent;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function id(): Identity
    {
        return $this->id;
    }

    public function contractId(): ContractId
    {
        return $this->contractId;
    }

    public function signatureSvg(): string
    {
        return $this->signatureSvg;
    }

    public function ipAddress(): ?string
    {
        return $this->ipAddress;
    }

    public function userAgent(): ?string
    {
        return $this->userAgent;
    }

    public function signedAt(): DateTimeImmutable
    {
        return $this->signedAt;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
