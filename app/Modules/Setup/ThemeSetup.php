<?php // app/Modules/Setup/ThemeSetup.php
declare(strict_types=1);

namespace FlocPress\Modules\Setup;

use FlocPress\Contracts\Hookable;
use FlocPress\Support\Config;
use FlocPress\Support\ThemeContext;

final class ThemeSetup implements Hookable
{
    public function __construct(
        private readonly Config $config,
        private readonly ThemeContext $context,
    ) {}

    public function hooks(): array
    {
        return [
            'after_setup_theme' => 'setup',
            'init'              => 'loadTranslations', // WP 6.7+: không load trước init
            'widgets_init'      => 'registerSidebars',
        ];
    }

    public function setup(): void
    {
        foreach ((array) $this->config->get('theme.supports', []) as $feature => $args) {
            $args === true ? add_theme_support($feature) : add_theme_support($feature, $args);
        }

        register_nav_menus((array) $this->config->get('theme.menus', []));

        foreach ((array) $this->config->get('theme.image_sizes', []) as $name => $size) {
            add_image_size($name, $size['width'], $size['height'], $size['crop'] ?? false);
        }

        add_editor_style($this->context->asset('dist/editor.css'));
        set_post_thumbnail_size(1200, 675, true);
    }

    public function loadTranslations(): void
    {
        load_theme_textdomain($this->context->textDomain, $this->context->path . '/languages');
    }

    public function registerSidebars(): void
    {
        foreach ((array) $this->config->get('theme.sidebars', []) as $sidebar) {
            register_sidebar($sidebar);
        }
    }
}

