<?php

declare(strict_types=1);

namespace Modules\Shared\Domain\Service;

interface TranslatorInterface
{
    public function trans(string $key, array $replace = [], ?string $locale = null): string;

}
