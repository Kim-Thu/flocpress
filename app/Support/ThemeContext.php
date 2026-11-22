<?php // app/Support/ThemeContext.php
declare(strict_types=1);

namespace FlocPress\Support;

final readonly class ThemeContext
{
    public function __construct(
        public string $path,
        public string $uri,
        public string $version,
        public string $textDomain,
        public bool $isDev = false,
    ) {}

    public function asset(string $relative): string
    {
        return $this->uri . '/' . ltrim($relative, '/');
    }
}

