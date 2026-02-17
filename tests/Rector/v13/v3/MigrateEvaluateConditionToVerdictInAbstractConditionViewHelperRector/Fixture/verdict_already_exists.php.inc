<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v3\MigrateEvaluateConditionToVerdictInAbstractConditionViewHelperRector\Fixture;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper;

class VerdictAlreadyExistsViewHelper extends AbstractConditionViewHelper
{
    protected static function evaluateCondition($arguments = null): bool
    {
        return (bool)$arguments['condition'];
    }

    public static function verdict(array $arguments, RenderingContextInterface $renderingContext): bool
    {
        return (bool)$arguments['myCustomCondition'];
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v3\MigrateEvaluateConditionToVerdictInAbstractConditionViewHelperRector\Fixture;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper;

class VerdictAlreadyExistsViewHelper extends AbstractConditionViewHelper
{
    public static function verdict(array $arguments, RenderingContextInterface $renderingContext): bool
    {
        return (bool)$arguments['myCustomCondition'];
    }
}
?>
