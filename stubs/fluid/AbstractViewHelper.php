<?php
declare(strict_types=1);

namespace TYPO3Fluid\Fluid\Core\ViewHelper;

abstract class AbstractViewHelper implements ViewHelperInterface
{
    protected $arguments;

    public function registerArgument($name, $type, $description, $required = false, $defaultValue = null): void
    {

    }

    public function initializeArguments()
    {

    }
}
