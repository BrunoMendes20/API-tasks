<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in([
        __DIR__ . '/app',
        __DIR__ . '/routes',
        __DIR__ . '/database',
    ])
    ->exclude([
        'storage',
        'bootstrap/cache',
        'vendor',
    ]);

return (new Config())
    ->setRiskyAllowed(false)
    ->setRules([
        '@PSR12' => true,
    ])
    ->setFinder($finder);
