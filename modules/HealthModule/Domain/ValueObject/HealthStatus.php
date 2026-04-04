<?php

declare(strict_types=1);

namespace Modules\HealthModule\Domain\ValueObject;

final readonly class HealthStatus
{
    public function __construct(
        public string $service,
        public bool $isHealthy,
        public ?string $message = null,
        public ?array $details = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'service' => $this->service,
            'healthy' => $this->isHealthy,
            'message' => $this->message,
            'details' => $this->details,
        ];
    }
}
