<?php // app/Contracts/Renderer.php
declare(strict_types=1);

namespace FlocPress\Contracts;

interface Renderer
{
    /** @param array<string, mixed> $data */
    public function render(string $view, array $data = []): string;
}

