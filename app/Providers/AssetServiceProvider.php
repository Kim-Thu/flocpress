<?php // app/Providers/AssetServiceProvider.php
declare(strict_types=1);

namespace FlocPress\Providers;

use FlocPress\Contracts\AssetManifest;
use FlocPress\Modules\Assets\{AssetManager, DevServerManifest, ViteManifest};
use FlocPress\Support\ThemeContext;

final class AssetServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->container->singleton(AssetManifest::class, function ($c): AssetManifest {
            /** @var ThemeContext $ctx */
            $ctx = $c->get(ThemeContext::class);

            return $ctx->isDev ? new DevServerManifest() : new ViteManifest($ctx);
        });

        $this->container->singleton(AssetManager::class);
    }

    public function boot(): void
    {
        $this->subscribe(AssetManager::class);
    }
}

