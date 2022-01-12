<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v4\AddSetConfigurationMethodToExceptionHandlerRector\Fixture;

use Exception;
use TYPO3\CMS\Frontend\ContentObject\AbstractContentObject;
use TYPO3\CMS\Frontend\ContentObject\Exception\ProductionExceptionHandler;

class MyCustomHandlerExtendingProductionHandler extends ProductionExceptionHandler
{
    public function handle(Exception $exception, AbstractContentObject $contentObject = null, $contentObjectConfiguration = [])
    {
        $foo = $this->configuration['foo'];
    }
}

?>
