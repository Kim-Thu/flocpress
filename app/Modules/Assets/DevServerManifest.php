<?php // app/Modules/Assets/DevServerManifest.php
declare(strict_types=1);

namespace FlocPress\Modules\Assets;

use FlocPress\Contracts\AssetManifest;

final class DevServerManifest implements AssetManifest
{
    public function __construct(private readonly string $origin = 'http://127.0.0.1:5173') {}

    public function entry(string $source): Asset
    {
        return new Asset(url: "{$this->origin}/{$source}", version: 'dev');
    }

    public function clientUrl(): string
    {
        return "{$this->origin}/@vite/client";
    }
}

