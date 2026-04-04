<?php
declare(strict_types=1);

namespace Modules\Shared\Infrastructure\Services;

use Closure;
use Illuminate\Support\Facades\Cache;

final class CacheService
{
    public function remember(string $group, string $key, Closure $callback, ?int $ttl = null): mixed
    {
        $store = Cache::getStore();

        $ttl = $ttl ?? config("cache-groups.{$group}.ttl", 3600);

        if (method_exists($store, 'tags')) {
            return Cache::tags([$group])->remember("{$group}:{$key}", $ttl, $callback);
        }

        return Cache::remember("{$group}:{$key}", $ttl, $callback);
    }

    public function forget(string $group, string $key): void
    {
        $store = Cache::getStore();

        if (method_exists($store, 'tags')) {
            Cache::tags([$group])->forget("{$group}:{$key}");
        } else {
            Cache::forget("{$group}:{$key}");
        }
    }

    public function flushGroup(string $group): void
    {
        $store = Cache::getStore();

        if (method_exists($store, 'tags')) {
            Cache::tags([$group])->flush();
        } else {
        }
    }
}
