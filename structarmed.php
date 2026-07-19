<?php

declare(strict_types=1);

use Boundwize\StructArmed\Architecture;
use Boundwize\StructArmed\Preset\Preset;
use Boundwize\StructArmed\Rule\Rules\Class_\MustBeFinalRule;

return Architecture::define()
    ->rule(
        'source.must_be_final',
        new MustBeFinalRule(layer: 'Source'),
    )
    ->skip([
        '*/MigratePluginContentElementAndPluginSubtypesRector/Assertions/extension*',
        'source.must_be_final' => [
            '*/Source/*',
            '*/Sources/*',
            __DIR__ . '/stubs',
        ],
    ])
    ->withPreset(Preset::PSR4());
