<?php // app/Support/Exceptions/NotFoundException.php
declare(strict_types=1);

namespace FlocPress\Support\Exceptions;

use RuntimeException;
use Psr\Container\NotFoundExceptionInterface;

final class NotFoundException extends RuntimeException implements NotFoundExceptionInterface {}

