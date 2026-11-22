<?php // app/Support/HookLoader.php
declare(strict_types=1);

namespace FlocPress\Support;

use InvalidArgumentException;
use FlocPress\Contracts\Hookable;

final class HookLoader
{
    /** @var list<array{0:string,1:callable,2:int,3:int}> */
    private array $registered = [];

    public function register(Hookable $module): void
    {
        foreach ($module->hooks() as $hook => $definition) {
            [$method, $priority, $args] = $this->normalize($definition);

            if (! method_exists($module, $method)) {
                throw new InvalidArgumentException(
                    sprintf('%s::%s() không tồn tại (hook "%s").', $module::class, $method, $hook)
                );
            }

            $callback = [$module, $method];

            add_filter($hook, $callback, $priority, $args);
            $this->registered[] = [$hook, $callback, $priority, $args];
        }
    }

    /** Cho phép gỡ toàn bộ hook — hữu ích khi test / hot-reload. */
    public function unregisterAll(): void
    {
        foreach ($this->registered as [$hook, $callback, $priority, $args]) {
            remove_filter($hook, $callback, $priority);
        }
        $this->registered = [];
    }

    /** @return array{0:string,1:int,2:int} */
    private function normalize(string|array $definition): array
    {
        if (is_string($definition)) {
            return [$definition, 10, 1];
        }

        return [$definition[0], $definition[1] ?? 10, $definition[2] ?? 1];
    }
}

