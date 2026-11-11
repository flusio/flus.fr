<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;
use Rector\Php81\Rector\Property\ReadOnlyPropertyRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/configuration',
        __DIR__ . '/lib',
        __DIR__ . '/public',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withPhpSets()
    ->withComposerBased(
        phpunit: true,
        twig: true,
    )
    ->withPreparedSets(
        deadCode: true,
        typeDeclarations: true,
    )
    ->withSkip([
        ClassPropertyAssignToConstructorPromotionRector::class,
        ReadOnlyPropertyRector::class,
    ]);
