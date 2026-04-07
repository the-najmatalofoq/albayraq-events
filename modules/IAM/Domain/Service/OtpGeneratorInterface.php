<?php
declare(strict_types=1);

namespace Modules\IAM\Domain\Service;

interface OtpGeneratorInterface
{
    public function generate(int $length = 6): string;
}
