<?php

declare(strict_types=1);

namespace Modules\Notification\Domain;

use Modules\Notification\Domain\ValueObject\DeviceTokenId;
use Modules\User\Domain\ValueObject\UserId;

final class DeviceToken
{
    private DeviceTokenId $id;
    private UserId $userId;
    private string $deviceId;
    private string $token;
    private string $platform;
    private ?string $deviceName;
    private bool $isActive;
    private ?\DateTimeImmutable $lastUsedAt;

    private function __construct(
        DeviceTokenId $id,
        UserId $userId,
        string $deviceId,
        string $token,
        string $platform,
        ?string $deviceName,
        bool $isActive,
        ?\DateTimeImmutable $lastUsedAt,
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->deviceId = $deviceId;
        $this->token = $token;
        $this->platform = $platform;
        $this->deviceName = $deviceName;
        $this->isActive = $isActive;
        $this->lastUsedAt = $lastUsedAt;
    }

    public static function register(
        UserId $userId,
        string $deviceId,
        string $token,
        string $platform,
        ?string $deviceName,
    ): self {
        return new self(
            DeviceTokenId::generate(),
            $userId,
            $deviceId,
            $token,
            $platform,
            $deviceName,
            true,
            null,
        );
    }

    public static function hydrate(
        DeviceTokenId $id,
        UserId $userId,
        string $deviceId,
        string $token,
        string $platform,
        ?string $deviceName,
        bool $isActive,
        ?\DateTimeImmutable $lastUsedAt
    ): self {
        return new self(
            $id,
            $userId,
            $deviceId,
            $token,
            $platform,
            $deviceName,
            $isActive,
            $lastUsedAt
        );
    }

    public function updateToken(string $newToken): void
    {
        $this->token = $newToken;
        $this->isActive = true;
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

    public function getDeviceId(): string
    {
        return $this->deviceId;
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
