<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v3\MigrateEvaluateConditionToVerdictInAbstractConditionViewHelperRector\Fixture;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper;

class AlreadyMigratedViewHelper extends AbstractConditionViewHelper
{
    public static function verdict(array $arguments, RenderingContextInterface $renderingContext): bool
    {
        return (bool)$arguments['condition'];
    }
}
?>
