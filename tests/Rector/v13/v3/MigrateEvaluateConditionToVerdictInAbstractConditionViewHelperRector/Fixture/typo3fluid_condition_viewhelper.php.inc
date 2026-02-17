<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v3\MigrateEvaluateConditionToVerdictInAbstractConditionViewHelperRector\Fixture;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper;

class MyConditionViewHelper extends AbstractConditionViewHelper
{
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('condition', 'bool', 'The condition', true);
    }

    protected static function evaluateCondition($arguments = null): bool
    {
        return (bool)$arguments['condition'];
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v3\MigrateEvaluateConditionToVerdictInAbstractConditionViewHelperRector\Fixture;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper;

class MyConditionViewHelper extends AbstractConditionViewHelper
{
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('condition', 'bool', 'The condition', true);
    }

    public static function verdict(array $arguments, RenderingContextInterface $renderingContext): bool
    {
        return (bool)$arguments['condition'];
    }
}
?>
