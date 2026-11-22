<?php // tests/Unit/ContainerTest.php
use FlocPress\Support\Container;
use FlocPress\Contracts\Renderer;
use FlocPress\Support\PhpRenderer;

it('autowires dependencies', function () {
    $c = new Container();
    $c->instance(FlocPress\Support\ThemeContext::class, new FlocPress\Support\ThemeContext(__DIR__, '', '1.0', 'flocpress'));
    $c->bind(Renderer::class, PhpRenderer::class);

    expect($c->get(PhpRenderer::class))->toBeInstanceOf(PhpRenderer::class);
});

it('returns same instance for singletons', function () {
    $c = new Container();
    $c->singleton(stdClass::class);
    expect($c->get(stdClass::class))->toBe($c->get(stdClass::class));
});

it('detects circular dependency', function () { /* ... */ })->throws(ContainerException::class);

