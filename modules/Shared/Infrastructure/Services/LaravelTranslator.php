<?php

declare(strict_types=1);

namespace Modules\Shared\Infrastructure\Services;

use Modules\Shared\Domain\Service\TranslatorInterface;
use Illuminate\Contracts\Translation\Translator;

final readonly class LaravelTranslator implements TranslatorInterface
{
    public function __construct(
        private Translator $translator
    ) {}

    public function trans(string $key, array $replace = [], ?string $locale = null): string
    {
        return (string) $this->translator->get($key, $replace, $locale);
    }


}
