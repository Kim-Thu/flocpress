<?php
declare(strict_types=1);

namespace FlocPress\Providers;

final class ContentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // TODO: Bind các class liên quan đến Post Type, Taxonomy ở đây
    }

    public function boot(): void
    {
        // TODO: Gọi $this->subscribe(...) khi có module sẵn sàng
    }
}