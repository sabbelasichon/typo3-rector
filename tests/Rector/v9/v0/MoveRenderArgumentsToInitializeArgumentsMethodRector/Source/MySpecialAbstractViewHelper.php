<?php
declare(strict_types=1);


namespace Ssch\TYPO3Rector\Tests\Rector\v9\v0\MoveRenderArgumentsToInitializeArgumentsMethodRector\Source;


use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

abstract class MySpecialAbstractViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('as', 'string', 'The name to access the variable in the template', false, 'items');
        $this->registerArgument('delimiter', 'string', 'The delimiter to explode the strings', false, '#');
    }
}
