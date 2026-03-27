<?php
// modules\Shared\Infrastructure\Services\Logger.php
declare(strict_types=1);

namespace Modules\Shared\Infrastructure\Services;

use Modules\Shared\Domain\Service\LoggerInterface;
use Illuminate\Support\Facades\Log;

final readonly class Logger implements LoggerInterface
{
    public function __construct(
        private ?string $channel = 'daily'
    ) {}

    public function emergency(string $message, array $context = []): void
    {
        Log::channel($this->channel)->emergency($message, $context);
    }

    public function alert(string $message, array $context = []): void
    {
        Log::channel($this->channel)->alert($message, $context);
    }

    public function critical(string $message, array $context = []): void
    {
        Log::channel($this->channel)->critical($message, $context);
    }

    public function error(string $message, array $context = []): void
    {
        Log::channel($this->channel)->error($message, $context);
    }

    public function warning(string $message, array $context = []): void
    {
        Log::channel($this->channel)->warning($message, $context);
    }

    public function notice(string $message, array $context = []): void
    {
        Log::channel($this->channel)->notice($message, $context);
    }

    public function info(string $message, array $context = []): void
    {
        Log::channel($this->channel)->info($message, $context);
    }

    public function debug(string $message, array $context = []): void
    {
        Log::channel($this->channel)->debug($message, $context);
    }

    public function log(string $level, string $message, array $context = []): void
    {
        Log::channel($this->channel)->log($level, $message, $context);
    }

    public function logException(\Throwable $e, array $extraContext = []): void
    {
        $context = array_merge([
            'exception' => get_class($e),
            'message'   => $e->getMessage(),
            'code'      => $e->getCode(),
            'file'      => $e->getFile(),
            'line'      => $e->getLine(),
            'trace'     => $e->getTraceAsString(),
            'previous'  => $e->getPrevious() ? get_class($e->getPrevious()) : null,
        ], $extraContext);

        $this->error($e->getMessage(), $context);
    }
}
