<?php // app/Modules/Content/PostType.php
declare(strict_types=1);

namespace FlocPress\Modules\Content;

final readonly class PostType
{
    public function __construct(public string $slug, public array $args) {}

    public static function fromConfig(string $slug, array $config): self
    {
        return new self($slug, array_merge([
            'public'       => true,
            'has_archive'  => true,
            'show_in_rest' => true,
            'menu_icon'    => 'dashicons-portfolio',
            'supports'     => ['title', 'editor', 'thumbnail', 'excerpt'],
            'rewrite'      => ['slug' => $slug, 'with_front' => false],
        ], $config));
    }
}

