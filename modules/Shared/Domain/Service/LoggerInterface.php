<?php
// Modules/Shared/Domain/Service/LoggerInterface.php
declare(strict_types=1);

namespace Modules\Shared\Domain\Service;

interface LoggerInterface
{
    public function emergency(string $message, array $context = []): void;
    public function alert(string $message, array $context = []): void;
    public function critical(string $message, array $context = []): void;
    public function error(string $message, array $context = []): void;
    public function warning(string $message, array $context = []): void;
    public function notice(string $message, array $context = []): void;
    public function info(string $message, array $context = []): void;
    public function debug(string $message, array $context = []): void;
    public function log(string $level, string $message, array $context = []): void;

    /**
     * Log an exception with full context
     */
    public function logException(\Throwable $e, array $extraContext = []): void;
}
