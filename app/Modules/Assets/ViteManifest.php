<?php // app/Modules/Assets/ViteManifest.php
declare(strict_types=1);

namespace FlocPress\Modules\Assets;

use FlocPress\Contracts\AssetManifest;
use FlocPress\Support\ThemeContext;
use RuntimeException;

final class ViteManifest implements AssetManifest
{
    /** @var array<string, array<string, mixed>> */
    private array $manifest;

    public function __construct(private readonly ThemeContext $context)
    {
        $file = $context->path . '/dist/.vite/manifest.json';

        if (! is_readable($file)) {
            throw new RuntimeException('Chưa build assets. Chạy: npm run build');
        }

        $this->manifest = (array) json_decode(
            (string) file_get_contents($file), true, 512, JSON_THROW_ON_ERROR
        );
    }

    public function entry(string $source): Asset
    {
        $chunk = $this->manifest[$source] ?? throw new RuntimeException("Entry không tồn tại: {$source}");

        return new Asset(
            url:     $this->context->asset('dist/' . $chunk['file']),
            version: $this->hashOf($chunk['file']),
            css:     array_map(
                fn (string $css): string => $this->context->asset('dist/' . $css),
                $chunk['css'] ?? []
            ),
        );
    }

    private function hashOf(string $file): string
    {
        // Filename đã chứa content-hash → cache busting miễn phí, không cần filemtime
        return preg_match('/[.-]([a-zA-Z0-9_-]{8})\.\w+$/', $file, $m) ? $m[1] : $this->context->version;
    }
}

