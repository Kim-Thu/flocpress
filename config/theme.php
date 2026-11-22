<?php // config/theme.php
declare(strict_types=1);

return [
    'supports' => [
        'title-tag'             => true,
        'post-thumbnails'       => true,
        'responsive-embeds'     => true,
        'align-wide'            => true,
        'editor-styles'         => true,
        'automatic-feed-links'  => true,
        'custom-logo'           => ['height' => 64, 'width' => 240, 'flex-width' => true],
        'html5'                 => ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script'],
    ],
    'menus' => [
        'primary' => 'Menu chính',
        'footer'  => 'Menu footer',
    ],
    'image_sizes' => [
        'card'    => ['width' => 640,  'height' => 400, 'crop' => true],
        'hero'    => ['width' => 1920, 'height' => 900, 'crop' => true],
    ],
    'sidebars' => [
        ['id' => 'sidebar-1', 'name' => 'Sidebar', 'before_widget' => '<div id="%1$s" class="c-widget %2$s">', 'after_widget' => '</div>', 'before_title' => '<h2 class="c-widget__title">', 'after_title' => '</h2>'],
    ],
];

