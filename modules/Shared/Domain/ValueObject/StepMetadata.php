<?php
// modules/Shared/Domain/ValueObject/StepMetadata.php
declare(strict_types=1);

namespace Modules\Shared\Domain\ValueObject;

use Modules\Shared\Domain\ValueObject;

final readonly class StepMetadata extends ValueObject
{
    public function __construct(
        public ?int $quizScore = null,
        public ?int $videoWatchPercent = null,
        public ?int $scrollDepth = null
    ) {}

    public function equals(ValueObject $other): bool
    {
        return $other instanceof self && (array)$this === (array)$other;
    }
}
