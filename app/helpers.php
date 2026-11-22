<?php // app/helpers.php
declare(strict_types=1);

use FlocPress\Contracts\Renderer;
use FlocPress\Kernel;

if (! function_exists('view')) {
    function view(string $template, array $data = []): void
    {
        echo Kernel::container()->get(Renderer::class)->render($template, $data); // phpcs:ignore
    }
}

if (! function_exists('e')) {
    function e(?string $value): string
    {
        return esc_html((string) $value);
    }
}

