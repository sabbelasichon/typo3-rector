<?php
declare(strict_types=1);


namespace TYPO3Fluid\Fluid\Core\ViewHelper;

if (class_exists(AbstractTagBasedViewHelper::class)) {
    return;
}

abstract class AbstractTagBasedViewHelper extends AbstractViewHelper
{

}
