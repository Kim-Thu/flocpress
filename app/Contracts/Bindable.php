<?php // app/Contracts/Bindable.php
declare(strict_types=1);

namespace FlocPress\Contracts;

use Closure;
use Psr\Container\ContainerInterface;

interface Bindable extends ContainerInterface
{
    public function bind(string $id, Closure|string|null $concrete = null, bool $shared = false): void;
    public function singleton(string $id, Closure|string|null $concrete = null): void;
    public function instance(string $id, mixed $instance): void;
}

