<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v4\MigrateRecordTooltipMethodToRecordIconAltTextMethodRector\Source;

final class CustomBackendUtility
{
    public static function getRecordToolTip(): string
    {
        return 'tooltip';
    }
}
