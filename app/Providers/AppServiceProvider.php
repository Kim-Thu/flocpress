<?php
declare(strict_types=1);

namespace FlocPress\Providers;

use FlocPress\Modules\Setup\ThemeSetup;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->container->singleton(ThemeSetup::class);
    }

    public function boot(): void
    {
        $this->subscribe(ThemeSetup::class);
    }
}