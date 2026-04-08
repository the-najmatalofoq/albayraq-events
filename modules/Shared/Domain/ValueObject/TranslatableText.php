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
        if (empty($this->values)) {
            throw new \InvalidArgumentException('Translatable text must not be empty.');
        }
    }
    public static function fromMixed(mixed $data): self
    {
        if (is_array($data)) {
            return new self($data);
        }

        if (is_string($data)) {

            $decoded = json_decode($data, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return new self($decoded);
            }

            $currentLocale = app()->getLocale();

            return new self([
                $currentLocale => $data,
            ]);
        }

        throw new \InvalidArgumentException('Invalid translatable text data.');
    }

    public static function fromArray(array $data): self
    {
        return new self($data);
    }

    public function toArray(): array
    {
        return $this->values;
    }

    public function getFor(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        return $this->values[$locale]
            ?? $this->values['en']
            ?? reset($this->values)
            ?? '';
    }

    public function __toString(): string
    {
        return json_encode($this->values, JSON_UNESCAPED_UNICODE) ?: '';
    }

    public function equals(ValueObject $other): bool
    {
        return $other instanceof self && $this->values === $other->values;
    }
}
