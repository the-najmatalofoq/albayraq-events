<?php
// modules/Shared/Domain/ValueObject/TranslatableText.php
declare(strict_types=1);

namespace Modules\Shared\Domain\ValueObject;

use Modules\Shared\Domain\ValueObject;

final readonly class TranslatableText extends ValueObject
{
    private function __construct(
        public array $values
    ) {
        if (!isset($this->values['ar']) || !isset($this->values['en'])) {
            throw new \InvalidArgumentException('Translatable text must contain "ar" and "en" keys.');
        }
    }

    public static function fromArray(array $data): self
    {
        return new self($data);
    }

    public function toArray(): array
    {
        return $this->values;
    }

    public function getFor(string $locale): string
    {
        return $this->values[$locale] ?? $this->values['en'];
    }

    public function equals(ValueObject $other): bool
    {
        return $other instanceof self && $this->values === $other->values;
    }
}
