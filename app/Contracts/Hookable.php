<?php // app/Contracts/Hookable.php
declare(strict_types=1);

namespace FlocPress\Contracts;

interface Hookable
{
    /**
     * ['hook_name' => 'method'] hoặc ['hook_name' => ['method', priority, acceptedArgs]]
     * @return array<string, string|array{0:string,1?:int,2?:int}>
     */
    public function hooks(): array;
}

