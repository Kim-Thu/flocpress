<?php // app/Kernel.php
declare(strict_types=1);

namespace FlocPress;

use FlocPress\Contracts\Bindable;
use FlocPress\Providers\ServiceProvider;
use FlocPress\Support\Config;
use FlocPress\Support\Container;
use FlocPress\Support\HookLoader;
use FlocPress\Support\ThemeContext;
use Psr\Container\ContainerInterface;

final class Kernel
{
    private static ?self $instance = null;
    private Bindable $container;

    private function __construct(private readonly ThemeContext $context) {}

    public static function boot(ThemeContext $context): self
    {
        if (self::$instance !== null) {
            return self::$instance;
        }

        $kernel = new self($context);
        $kernel->bootstrap();

        return self::$instance = $kernel;
    }

    public static function container(): Bindable
    {
        return self::$instance->container ?? throw new \RuntimeException('Theme chưa boot.');
    }

    private function bootstrap(): void
    {
        $container = new Container();
        $container->instance(Bindable::class, $container);
        $container->instance(ContainerInterface::class, $container);
        $container->instance(ThemeContext::class, $this->context);
        $container->instance(Config::class, Config::fromDirectory($this->context->path . '/config'));
        $container->singleton(HookLoader::class);

        $this->container = $container;

        $providers = $container->get(Config::class)->get('app.providers', []);

        $instances = [];
        foreach ($providers as $class) {
            $provider = new $class($container);
            $provider->register();
            $instances[] = $provider;
        }

        foreach ($instances as $provider) {
            $provider->boot();
        }
    }
}
