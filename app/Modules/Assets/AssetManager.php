<?php // app/Modules/Assets/AssetManager.php
declare(strict_types=1);

namespace FlocPress\Modules\Assets;

use FlocPress\Contracts\AssetManifest;
use FlocPress\Contracts\Hookable;
use FlocPress\Support\Config;

final class AssetManager implements Hookable
{
    public function __construct(
        private readonly AssetManifest $manifest,
        private readonly Config $config,
    ) {}

    public function hooks(): array
    {
        return [
            'wp_enqueue_scripts'         => 'enqueueFrontend',
            'enqueue_block_editor_assets'=> 'enqueueEditor',
            'wp_head'                    => ['preloadFonts', 1],
        ];
    }

    public function enqueueFrontend(): void
    {
        $asset = $this->manifest->entry('resources/scripts/main.js');

        foreach ($asset->css as $i => $css) {
            wp_enqueue_style("flocpress-main-{$i}", $css, [], $asset->version);
        }

        // CSS đi kèm entry JS trong manifest
        $style = $this->manifest->entry('resources/styles/main.scss');
        wp_enqueue_style('flocpress', $style->url, [], $style->version);

        if ($this->manifest instanceof DevServerManifest) {
            $this->enqueueModule('flocpress-vite', $this->manifest->clientUrl(), 'dev');
        }

        $this->enqueueModule('flocpress-main', $asset->url, $asset->version);

        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
    }

    public function enqueueEditor(): void
    {
        $asset = $this->manifest->entry('resources/scripts/editor.js');
        $this->enqueueModule('flocpress-editor', $asset->url, $asset->version);
    }

    public function preloadFonts(): void
    {
        foreach ((array) $this->config->get('assets.preload_fonts', []) as $font) {
            printf(
                '<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
                esc_url($font)
            );
        }
    }

    private function enqueueModule(string $handle, string $src, string $ver): void
    {
        if (function_exists('wp_enqueue_script_module')) { // WP 6.5+
            wp_enqueue_script_module($handle, $src, [], $ver);
            return;
        }
        wp_enqueue_script($handle, $src, [], $ver, true);
    }
}

