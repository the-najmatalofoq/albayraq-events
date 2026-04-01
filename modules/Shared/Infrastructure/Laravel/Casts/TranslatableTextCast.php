<?php
declare(strict_types=1);

namespace Modules\Shared\Infrastructure\Laravel\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use InvalidArgumentException;

/**
 * Casts a JSON database column directly into the TranslatableText Domain ValueObject.
 */
class TranslatableTextCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): ?TranslatableText
    {
        if (empty($value)) {
            return null;
        }

        $data = is_string($value) ? json_decode($value, true) : $value;

        if (!is_array($data)) {
            return null;
        }

        try {
            return TranslatableText::fromArray($data);
        } catch (InvalidArgumentException $e) {
            return null;
        }
    }

    /**
     * تحويل الـ Value Object إلى JSON عند الحفظ في قاعدة البيانات
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof TranslatableText) {
           
            return json_encode($value->toArray(), JSON_UNESCAPED_UNICODE);
        }

        if (is_array($value)) {
            return json_encode($value, JSON_UNESCAPED_UNICODE);
        }

        return $value;
    }
}
