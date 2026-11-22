<?php // app/Modules/Content/PostTypeRegistrar.php
declare(strict_types=1);

namespace FlocPress\Modules\Content;

use FlocPress\Contracts\Hookable;
use FlocPress\Support\Config;

final class PostTypeRegistrar implements Hookable
{
    public function __construct(private readonly Config $config) {}

    public function hooks(): array
    {
        return ['init' => ['register', 5]];
    }

    public function register(): void
    {
        foreach ((array) $this->config->get('content.post_types', []) as $slug => $config) {
            $type = PostType::fromConfig($slug, $config);

            if (! post_type_exists($type->slug)) {
                register_post_type($type->slug, $type->args);
            }
        }
    }
}

