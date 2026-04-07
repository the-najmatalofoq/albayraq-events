<?php
declare(strict_types=1);

namespace Modules\IAM\Infrastructure\Services;

use Modules\IAM\Domain\Service\OtpGeneratorInterface;

final readonly class RandomOtpGenerator implements OtpGeneratorInterface
{
    public function generate(int $length = 6): string
    {
        $min = (int) (10 ** ($length - 1));
        $max = (int) (10 ** $length - 1);
        return (string) random_int($min, $max);
    }
}
