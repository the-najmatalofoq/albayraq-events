<?php

declare(strict_types=1);

namespace Modules\Notification\Domain;

use Modules\Notification\Domain\ValueObject\DeviceTokenId;
use Modules\User\Domain\ValueObject\UserId;

final class DeviceToken
{
    private DeviceTokenId $id;
    private UserId $userId;
    private string $token;
    private string $platform;
    private ?string $deviceName;
    private bool $isActive;
    private ?\DateTimeImmutable $lastUsedAt;

    private function __construct(
        DeviceTokenId $id,
        UserId $userId,
        string $token,
        string $platform,
        ?string $deviceName,
        bool $isActive,
        ?\DateTimeImmutable $lastUsedAt,
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->token = $token;
        $this->platform = $platform;
        $this->deviceName = $deviceName;
        $this->isActive = $isActive;
        $this->lastUsedAt = $lastUsedAt;
    }

    public static function register(
        UserId $userId,
        string $token,
        string $platform,
        ?string $deviceName,
    ): self {
        return new self(
            DeviceTokenId::generate(),
            $userId,
            $token,
            $platform,
            $deviceName,
            true,
            null,
        );
    }

    public function revoke(): void
    {
        $this->isActive = false;
    }

    public function markUsed(): void
    {
        $this->lastUsedAt = new \DateTimeImmutable();
    }

    public function getId(): DeviceTokenId
    {
        return $this->id;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getPlatform(): string
    {
        return $this->platform;
    }

    public function getDeviceName(): ?string
    {
        return $this->deviceName;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function getLastUsedAt(): ?\DateTimeImmutable
    {
        return $this->lastUsedAt;
    }
}
