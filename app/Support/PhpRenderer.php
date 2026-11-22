<?php // app/Support/PhpRenderer.php
declare(strict_types=1);

namespace FlocPress\Support;

use InvalidArgumentException;
use FlocPress\Contracts\Renderer;
use RuntimeException;

final class PhpRenderer implements Renderer
{
    public function __construct(private readonly ThemeContext $context) {}

    public function render(string $view, array $data = []): string
    {
        if (preg_match('/^[a-z0-9][a-z0-9._-]*$/i', $view) !== 1 || str_contains($view, '..')) {
            throw new InvalidArgumentException("Tên view không hợp lệ: {$view}");
        }

        $file = $this->context->path . '/resources/views/' . str_replace('.', '/', $view) . '.php';

        if (! is_readable($file)) {
            throw new RuntimeException("View không tồn tại: {$view}");
        }

        extract($data, EXTR_SKIP);
        ob_start();
        include $file;

        return (string) ob_get_clean();
    }
}

