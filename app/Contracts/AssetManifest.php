<?php // app/Contracts/AssetManifest.php
declare(strict_types=1);

namespace FlocPress\Contracts;

use FlocPress\Modules\Assets\Asset;

interface AssetManifest
{
    public function entry(string $source): Asset;
}

