<?php // app/Support/Container.php
declare(strict_types=1);

namespace FlocPress\Support;

use Closure;
use FlocPress\Contracts\Bindable;
use FlocPress\Support\Exceptions\ContainerException;
use FlocPress\Support\Exceptions\NotFoundException;
use ReflectionClass;
use ReflectionNamedType;

final class Container implements Bindable
{
    /** @var array<string, array{concrete: Closure, shared: bool}> */
    private array $bindings = [];

    /** @var array<string, mixed> */
    private array $instances = [];

    /** @var array<string, true> */
    private array $building = [];

    public function bind(string $id, Closure|string|null $concrete = null, bool $shared = false): void
    {
        $target = $concrete ?? $id;

        $this->bindings[$id] = [
            'concrete' => $target instanceof Closure
                ? $target
                : fn (self $c): object => $c->build($target),
            'shared'   => $shared,
        ];

        unset($this->instances[$id]);
    }

    public function singleton(string $id, Closure|string|null $concrete = null): void
    {
        $this->bind($id, $concrete, true);
    }

    public function instance(string $id, mixed $instance): void
    {
        $this->instances[$id] = $instance;
    }

    public function get(string $id): mixed
    {
        if (array_key_exists($id, $this->instances)) {
            return $this->instances[$id];
        }

        if (isset($this->building[$id])) {
            throw new ContainerException("Circular dependency detected: {$id}");
        }

        $this->building[$id] = true;

        try {
            $binding = $this->bindings[$id] ?? null;
            $object  = $binding !== null ? ($binding['concrete'])($this) : $this->build($id);

            if ($binding !== null && $binding['shared']) {
                $this->instances[$id] = $object;
            }

            return $object;
        } finally {
            unset($this->building[$id]);
        }
    }

    public function has(string $id): bool
    {
        return isset($this->bindings[$id]) || isset($this->instances[$id]) || class_exists($id);
    }

    /** Autowiring qua Reflection — chỉ resolve class-typed params. */
    private function build(string $class): object
    {
        if (! class_exists($class)) {
            throw new NotFoundException("Không tìm thấy binding hoặc class: {$class}");
        }

        $ref = new ReflectionClass($class);

        if (! $ref->isInstantiable()) {
            throw new ContainerException("Không thể khởi tạo {$class}. Hãy bind interface trước.");
        }

        $ctor = $ref->getConstructor();

        if ($ctor === null) {
            return new $class();
        }

        $args = [];

        foreach ($ctor->getParameters() as $param) {
            $type = $param->getType();

            if ($type instanceof ReflectionNamedType && ! $type->isBuiltin()) {
                $args[] = $this->get($type->getName());
                continue;
            }

            if ($param->isDefaultValueAvailable()) {
                $args[] = $param->getDefaultValue();
                continue;
            }

            if ($type !== null && $type->allowsNull()) {
                $args[] = null;
                continue;
            }

            throw new ContainerException(
                "Không autowire được \${$param->getName()} của {$class}. Cần bind thủ công."
            );
        }

        return $ref->newInstanceArgs($args);
    }
}

