<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v4\AddSetConfigurationMethodToExceptionHandlerRector\Fixture;

use Exception;
use TYPO3\CMS\Frontend\ContentObject\AbstractContentObject;
use TYPO3\CMS\Frontend\ContentObject\Exception\ExceptionHandlerInterface;

class MyAlreadyMigratedExceptionHandler implements ExceptionHandlerInterface
{
    /**
     * @var mixed[]
     */
    private array $configuration;

    public function setConfiguration(array $configuration): void
    {
        $this->configuration = $configuration;
    }

    public function handle(Exception $exception, AbstractContentObject $contentObject = null, $contentObjectConfiguration = [])
    {
        $foo = $this->configuration['foo'];
    }
}

?>
