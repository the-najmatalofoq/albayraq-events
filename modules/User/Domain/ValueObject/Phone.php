<?php

declare(strict_types=1);

namespace Modules\User\Domain\ValueObject;

use InvalidArgumentException;
use Modules\Shared\Domain\ValueObject;

final readonly class Phone extends ValueObject
{
    public string $value;

    public function __construct(string $value)
    {
        $normalized = $this->normalize($value);

        if (!$this->isValid($normalized)) {
            throw new InvalidArgumentException("phone is not valid");
        }

        $this->value = $normalized;
    }

    /**
     * توحيد صيغة الرقم لتكون دائماً 05xxxxxxxx
     */
    private function normalize(string $value): string
    {
        // 1. إزالة أي رموز أو مسافات (مثل + أو - أو فراغات)
        $digits = preg_replace('/[^0-9]/', '', $value);

        // 2. تحويل الصيغة الدولية (9665) إلى الصيغة المحلية (05) لتوحيد البحث في الداتابيز
        if (str_starts_with($digits, '9665')) {
            return '0' . substr($digits, 3);
        }

        // 3. إذا بدأ بـ 5 مباشرة (بدون 0)، أضيفي الصفر
        if (str_starts_with($digits, '5') && strlen($digits) === 9) {
            return '0' . $digits;
        }

        return $digits;
    }

    private function isValid(string $value): bool
    {
        // التحقق النهائي: يجب أن يبدأ بـ 05 ويتكون من 10 أرقام بالضبط
        return preg_match('/^05\d{8}$/', $value) === 1;
    }

    public function equals(ValueObject $other): bool
    {
        return $other instanceof self && $this->value === $other->value;
    }

    public function toString(): string
    {
        return $this->value;
    }
}
