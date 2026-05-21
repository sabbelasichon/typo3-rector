<?php

declare(strict_types=1);

use Boundwize\StructArmed\Architecture;
use Boundwize\StructArmed\Preset\Preset;

return Architecture::define()
    ->skip([
        '*/MigratePluginContentElementAndPluginSubtypesRector/Assertions/extension*',
    ])
    ->withPreset(Preset::PSR4());
