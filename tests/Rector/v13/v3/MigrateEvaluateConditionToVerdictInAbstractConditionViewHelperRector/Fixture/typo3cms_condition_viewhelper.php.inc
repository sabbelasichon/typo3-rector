<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v3\MigrateEvaluateConditionToVerdictInAbstractConditionViewHelperRector\Fixture;

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper;

class AnotherConditionViewHelper extends AbstractConditionViewHelper
{
    protected static function evaluateCondition($arguments = null): bool
    {
        return isset($arguments['value']) && $arguments['value'] > 0;
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v3\MigrateEvaluateConditionToVerdictInAbstractConditionViewHelperRector\Fixture;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper;

class AnotherConditionViewHelper extends AbstractConditionViewHelper
{
    public static function verdict(array $arguments, RenderingContextInterface $renderingContext): bool
    {
        return isset($arguments['value']) && $arguments['value'] > 0;
    }
}
?>
