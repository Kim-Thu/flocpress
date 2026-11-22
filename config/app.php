<?php // config/app.php
declare(strict_types=1);

return [
    'providers' => [
        FlocPress\Providers\AppServiceProvider::class,
        FlocPress\Providers\AssetServiceProvider::class,
        FlocPress\Providers\ContentServiceProvider::class,
        FlocPress\Providers\PerformanceServiceProvider::class,
    ],
];

