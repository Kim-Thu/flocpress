<?php // app/Modules/Assets/Asset.php
declare(strict_types=1);

namespace FlocPress\Modules\Assets;

final readonly class Asset
{
    /** @param list<string> $css */
    public function __construct(
        public string $url,
        public string $version,
        public array $css = [],
        public bool $isModule = true,
    ) {}
}

