<?php
declare(strict_types=1);

namespace FlocPress\Providers;

use FlocPress\Contracts\Bindable;
use FlocPress\Contracts\Hookable;
use FlocPress\Support\HookLoader;

abstract class ServiceProvider
{
    public function __construct(protected readonly Bindable $container) {}

    public function register(): void {}

    abstract public function boot(): void;

    protected function subscribe(string $module): void
    {
        $instance = $this->container->get($module);
        $this->container->get(HookLoader::class)->register($instance);
    }
}